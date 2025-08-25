# File: realtime_detector.py
import cv2
from ultralytics import YOLO

# --- 1. DEFINE THE MODEL PATH ---
# Use the full path to your trained model
MODEL_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\training_results\final_balanced_run\weights\best.pt"

print(f"Loading model from: {MODEL_PATH}")

# --- 2. LOAD THE TRAINED YOLO MODEL ---
try:
    model = YOLO(MODEL_PATH)
    print("Model loaded successfully.")
except Exception as e:
    print(f"FATAL ERROR: Failed to load the model. Error: {e}")
    exit()

# --- 3. SETUP VIDEO CAPTURE ---
cap = cv2.VideoCapture(r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\test-video.mp4")


print("Starting real-time detection... Press 'q' to exit.")

# --- 4. REAL-TIME DETECTION LOOP ---
while True:
    success, frame = cap.read()
    if not success:
        break

    # --- Run YOLO detection on the frame ---
    # The model will predict the class (Attentive/Not-Attentive)
    results = model(frame, verbose=False) # Set verbose=False to clean up terminal output

    # --- Get the first result (since we process one image at a time) ---
    result = results[0]
    
    # --- Visualize the results on the frame ---
    for box in result.boxes:
        # Get bounding box coordinates
        x1, y1, x2, y2 = [int(val) for val in box.xyxy[0]]
        
        # Get confidence score and class name
        confidence = box.conf[0]
        class_id = int(box.cls[0])
        class_name = model.names[class_id]

        # --- Draw the bounding box ---
        # Set color based on class
        if class_name.lower() == 'attentive':
            color = (0, 255, 0) # Green for Attentive
        else:
            color = (0, 0, 255) # Red for Not-Attentive
        
        cv2.rectangle(frame, (x1, y1), (x2, y2), color, 2)
        
        # --- Create the label text (Class + Confidence) ---
        label = f"{class_name}: {confidence:.2f}"
        cv2.putText(frame, label, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.7, color, 2)
        
    # Display the annotated frame
    cv2.imshow("Real-Time Attention Detection", frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

# --- 5. CLEANUP ---
cap.release()
cv2.destroyAllWindows()
print("Detection stopped and resources released.")