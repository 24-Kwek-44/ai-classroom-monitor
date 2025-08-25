from ultralytics import YOLO
import os
import torch

# --- 1. SETUP AND VERIFICATION ---
print("="*50)
print("--- UTAR SERVER - MAX ACCURACY TRAINING (Quadro P4000) ---")

if torch.cuda.is_available():
    gpu_name = torch.cuda.get_device_name(0)
    total_mem = torch.cuda.get_device_properties(0).total_memory / (1024**3)
    print(f"GPU DETECTED: {gpu_name} ({total_mem:.2f} GB VRAM)")
else:
    print("FATAL ERROR: NO GPU DETECTED.")
    exit()

data_yaml_path = "My-First-Project-1/data.yaml"
# Using yolo11x.pt - This is the largest model that will reliably fit
# on the Quadro P4000's 8 GB of VRAM for this task.
base_model = "yolo11x.pt"

print(f"Using dataset: {data_yaml_path}")
print(f"Using base model: {base_model}")
print("="*50)

# --- 2. MODEL TRAINING ---
model = YOLO(base_model) 

print("\n--- Starting Final Training Run ---")
results = model.train(
    # --- Basic Parameters ---
    data=data_yaml_path,
    epochs=150,
    imgsz=640,
    project="training_results",
    name="attention_model_final_yolo11x",

    # --- Augmentation & Hyperparameters ---
    optimizer='AdamW', 
    lr0=0.005,
    # Batch size is critical for 8GB VRAM. Start with 4.
    batch=4,
    patience=50,
    close_mosaic=15
)

print("\n--- FINAL TRAINING COMPLETE ---")
print(f"Best model saved in 'training_results/attention_model_final_yolo11x'")