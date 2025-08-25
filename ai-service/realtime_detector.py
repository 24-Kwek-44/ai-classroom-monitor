# File: realtime_detector.py
import cv2
from ultralytics import YOLO
import requests
import time
import json

# --- 1. CONFIGURATION ---
MODEL_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\training_results\final_balanced_run\weights\best.pt"
VIDEO_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\test-video.mp4"
API_ENDPOINT = "http://127.0.0.1:8000/api_receiver.php"
CONFIDENCE_THRESHOLD = 0.01
API_CALL_INTERVAL = 2
last_api_call_time = 0

# --- 2. INITIALIZATION ---
print(f"Loading model from: {MODEL_PATH}")
model = YOLO(MODEL_PATH)
print("Model loaded successfully.")

print(f"Opening video file: {VIDEO_PATH}")
cap = cv2.VideoCapture(VIDEO_PATH)
if not cap.isOpened():
    print(f"FATAL ERROR: Could not open video file.")
    exit()

print("Starting real-time detection... Press 'q' to exit.")

# --- 3. REAL-TIME DETECTION LOOP ---
while cap.isOpened(): # <--- SLIGHT CHANGE: Loop while the capture is open
    success, frame = cap.read()
    if not success:
        print("End of video file or failed to read frame. Exiting.")
        break

    # Run YOLO detection on the frame
    results = model(frame, verbose=False)
    result = results[0]

    detected_students = []
    for box in result.boxes:
        confidence = float(box.conf[0])
        if confidence > CONFIDENCE_THRESHOLD:
            class_id = int(box.cls[0])
            class_name = model.names[class_id]
            x1, y1, x2, y2 = [int(val) for val in box.xyxy[0]]

            student_id = f"student_{x1}_{y1}"
            detected_students.append({
                'student_identifier': student_id,
                'status': class_name,
                'confidence': f"{confidence:.2f}"
            })

            color = (0, 255, 0) if class_name.lower() == 'attentive' else (0, 0, 255)
            cv2.rectangle(frame, (x1, y1), (x2, y2), color, 2)
            label = f"{class_name}: {confidence:.2f}"
            cv2.putText(frame, label, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.7, color, 2)

    current_time = time.time()
    if (current_time - last_api_call_time) > API_CALL_INTERVAL:
        if detected_students:
            try:
                headers = {'Content-Type': 'application/json'}
                response = requests.post(API_ENDPOINT, data=json.dumps(detected_students), headers=headers)
                print(f"API CALL SUCCESS: Sent data for {len(detected_students)} student(s). Server responded with status: {response.status_code}")
                last_api_call_time = current_time
            except requests.exceptions.RequestException as e:
                print(f"API CALL FAILED: Could not connect to the server. Error: {e}")

    # Display the annotated frame
    cv2.imshow("Real-Time Attention Detection", frame)

    # SLIGHT CHANGE: Increased waitKey delay to 25ms, which is standard for ~40 FPS video
    if cv2.waitKey(25) & 0xFF == ord('q'):
        break

# --- 4. CLEANUP ---
cap.release()
cv2.destroyAllWindows()
print("Detection stopped and resources released.")