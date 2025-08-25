from ultralytics import YOLO
from roboflow import Roboflow
import os
import shutil

# --- 1. DATASET SETUP ---
# It is highly recommended to use the dataset you have already downloaded and balanced
# to save time and API calls.
# We will use the local path directly.

# Path to the data.yaml file of the dataset you downloaded and balanced
# The script assumes the 'My-First-Project-1' folder is in the same directory as this script.
dataset_folder = "My-First-Project-1" 
data_yaml_path = os.path.join(dataset_folder, "data.yaml")

print("="*50)
print("--- FINAL TRAINING SCRIPT ---")
print(f"Using local balanced dataset from: {os.path.abspath(dataset_folder)}")
print(f"Path to data.yaml: {data_yaml_path}")
print("="*50)

# Verify the path exists before starting
if not os.path.exists(data_yaml_path):
    print(f"ERROR: 'data.yaml' not found at the specified path.")
    print("Please make sure the dataset folder 'My-First-Project-1' is in the 'ai-service' directory.")
    exit()


# --- 2. MODEL TRAINING ---
print("\n--- Starting Final Model Training on Balanced Dataset ---")

# Load the official yolo11n as our starting point for transfer learning
model = YOLO("yolo11n.pt") 

# Train the model with our final set of parameters
results = model.train(
    # --- Basic Parameters ---
    data=data_yaml_path,
    epochs=75,
    imgsz=640,
    project="training_results",
    name="final_balanced_run", # A new, clear name for our best run

    # --- Augmentation Parameters (for diversity) ---
    degrees=15.0,
    translate=0.1,
    scale=0.5,
    fliplr=0.5,
    mosaic=1.0,
    mixup=0.1,

    # --- Key Hyperparameters (for optimization) ---
    optimizer='AdamW', 
    lr0=0.01,
    lrf=0.01,
    momentum=0.937,
    weight_decay=0.0005,
    batch=16 # If you get memory errors on your machine, try reducing this to 8
)

print("\n--- Final Training Complete ---")
print(f"Best model saved in 'training_results/final_balanced_run'")