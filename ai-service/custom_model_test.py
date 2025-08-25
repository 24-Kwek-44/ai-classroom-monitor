from ultralytics import YOLO
import cv2
import os

# --- 1. LOAD YOUR CUSTOM MODEL ---
model_path = "training_results/behavior_detection_run12/weights/best.pt" 

print("="*50)
print(f"ATTEMPTING TO LOAD CUSTOM MODEL FROM: {os.path.abspath(model_path)}")
print("="*50)

if not os.path.exists(model_path):
    print("ERROR: The custom model file was not found at the specified path.")
    print("Please check the folder 'training_results' and verify the path.")
    exit()

try:
    model = YOLO(model_path)
    print("\nSUCCESS: Custom model loaded successfully.")
    print("The model knows the following classes:", model.names)
    print("="*50)
except Exception as e:
    print(f"Error loading model: {e}")
    exit()

# --- 2. PERFORM INFERENCE ---
image_path = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\My-First-Project-1\valid\images\061ee3d2dce923e91c6cb264c49b75ee_jpg.rf.4757141808fdd23e948d2f9b0af8fae8.jpg"
print(f"\n--- Running inference on '{image_path}' ---")

# ... (rest of the code is the same) ...
try:
    results = model(image_path)
except FileNotFoundError:
    print(f"Error: Test image not found at '{image_path}'")
    exit()

# --- 3. DISPLAY RESULTS ---
annotated_frame = results[0].plot()

print("\n--- Detections ---")
for box in results[0].boxes:
    class_name = model.names[int(box.cls)]
    confidence = box.conf[0]
    print(f"Detected '{class_name}' with confidence {confidence:.2f}")

cv2.imshow("Custom Model Detections", annotated_frame)
output_path = "custom_model_output.jpg"
cv2.imwrite(output_path, annotated_frame)
print(f"\nOutput image saved as '{output_path}'")

print("Press any key to exit.")
cv2.waitKey(0) 
cv2.destroyAllWindows()