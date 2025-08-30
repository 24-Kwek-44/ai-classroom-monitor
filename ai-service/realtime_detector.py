# File: realtime_detector.py
import cv2
import requests
import time
import json
from ultralytics import YOLO

# --- 1. CONFIGURATION ---
MODEL_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\training_results\final_balanced_run\weights\best.pt"
VIDEO_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\classroom_video_3.mp4"
API_ENDPOINT = "http://127.0.0.1:8000/api_receiver.php"
CONFIDENCE_THRESHOLD = 0
API_CALL_INTERVAL = 2
last_api_call_time = 0

# --- 2. INITIALIZATION ---
model = YOLO(MODEL_PATH)
cap = cv2.VideoCapture(VIDEO_PATH)
if not cap.isOpened():
    print("DETECTOR ERROR: Could not open video file.")
    exit()

frame_count = 0
fps = cap.get(cv2.CAP_PROP_FPS)

print("DETECTOR SCRIPT: Starting analysis...")

# --- 3. MAIN DETECTION LOOP ---
while cap.isOpened():
    success, frame = cap.read()
    if not success:
        print("DETECTOR SCRIPT: Video finished.")
        break
    
    frame_count += 1
    
    # Run the expensive YOLO detection
    results = model(frame, verbose=False)
    
    # --- Save raw detection data for the streamer script ---
    # This includes coordinates, confidence, and class ID
    # This is very fast.
    detections = results[0].boxes.data.cpu().numpy().tolist()
    with open('temp_detections.json', 'w') as f:
        json.dump(detections, f)
    
    # --- Send summary data to Laravel periodically ---
    current_time = time.time()
    if (current_time - last_api_call_time) > API_CALL_INTERVAL:
        attentive_count, total_detected = 0, 0
        for box in results[0].boxes:
            if float(box.conf[0]) > CONFIDENCE_THRESHOLD:
                total_detected += 1
                if model.names[int(box.cls[0])].lower() == 'attentive':
                    attentive_count += 1
        
        payload = {
            'timestamp': round(frame_count / fps if fps > 0 else 0),
            'attentiveness_percentage': round((attentive_count / total_detected) * 100) if total_detected > 0 else 0,
            'attentive_count': attentive_count,
            'total_detected': total_detected
        }
        try:
            requests.post(API_ENDPOINT, json=payload, timeout=0.5)
            print(f"DETECTOR SCRIPT: Sent data: {payload}")
            last_api_call_time = current_time
        except requests.exceptions.RequestException:
            print("DETECTOR SCRIPT: API call failed.")
    
    # No cv2.imshow() here. This script has no window.
    # We add a tiny delay to prevent it from running too fast and overloading the CPU
    time.sleep(0.01)

# --- 4. CLEANUP ---
cap.release()
# Clear the temp file to signal the streamer to stop
with open('temp_detections.json', 'w') as f:
    json.dump([], f)
print("DETECTOR SCRIPT: Finished.")