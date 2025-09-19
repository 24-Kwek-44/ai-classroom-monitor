from ultralytics import YOLO
import os
import torch

# ==========================================================
# 1. SETUP AND VERIFICATION
# Purpose: Ensure the system has a compatible GPU before training.
# ==========================================================
print("="*50)
print("--- UTAR SERVER - MAX ACCURACY TRAINING (Quadro P4000) ---")

# Check if CUDA (GPU support) is available
if torch.cuda.is_available():
    # Retrieve GPU name and VRAM size for logging
    gpu_name = torch.cuda.get_device_name(0)
    total_mem = torch.cuda.get_device_properties(0).total_memory / (1024**3)
    print(f"GPU DETECTED: {gpu_name} ({total_mem:.2f} GB VRAM)")
else:
    # Exit early if no GPU is found (training on CPU would be too slow)
    print("FATAL ERROR: NO GPU DETECTED.")
    exit()

# Path to dataset configuration (YOLO expects a data.yaml file)
data_yaml_path = "My-First-Project-1/data.yaml"

# Base pretrained YOLO model (YOLOv11x provides higher accuracy at cost of speed)
base_model = "yolo11x.pt"

print(f"Using dataset: {data_yaml_path}")
print(f"Using base model: {base_model}")
print("="*50)

# ==========================================================
# 2. MODEL TRAINING
# Purpose: Initialize YOLO model with the chosen weights and
#          start training with custom hyperparameters.
# ==========================================================
model = YOLO(base_model)   # Load base model

print("\n--- Starting Final Training Run ---")
results = model.train(
    # --- Basic Parameters ---
    data=data_yaml_path,           # Dataset configuration file
    epochs=150,                    # Number of training epochs
    imgsz=640,                     # Input image resolution
    project="training_results",    # Output folder
    name="attention_model_final_yolo11x",  # Subfolder for this run

    # --- Augmentation & Hyperparameters ---
    optimizer='AdamW',             # Optimizer (better generalization than SGD)
    lr0=0.005,                     # Initial learning rate
    batch=4,                       # Batch size (fits within 8GB VRAM limit)
    patience=50,                   # Early stopping if no improvement
    close_mosaic=15                # Disable mosaic augmentation after 15 epochs
)

# ==========================================================
# 3. TRAINING COMPLETE
# Purpose: Provide user feedback on completion and where best model is saved.
# ==========================================================
print("\n--- FINAL TRAINING COMPLETE ---")
print(f"Best model saved in 'training_results/attention_model_final_yolo11x'")
