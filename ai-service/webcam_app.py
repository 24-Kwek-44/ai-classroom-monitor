# File: webcam_app.py
from flask import Flask, Response
import cv2
import requests
import time
import json
from ultralytics import YOLO
import datetime # Import datetime for better timestamps

# --- 1. UNIFIED CONFIGURATION ---
# No longer need VIDEO_PATH, we define the webcam index instead
WEBCAM_INDEX = 1
MODEL_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\training_results\final_balanced_run\weights\best.pt"

# API Configuration
API_ENDPOINT = "http://127.0.0.1:8000/api_receiver.php"
API_CALL_INTERVAL = 2  # seconds

# Model Configuration
CONFIDENCE_THRESHOLD = 0
CLASS_NAMES = {0: 'Attentive', 1: 'Not-Attentive'} 

# --- 2. INITIALIZATION ---
app = Flask(__name__)
model = YOLO(MODEL_PATH)
print("APP: YOLO Model loaded successfully.")

# --- 3. CORE PROCESSING AND STREAMING FUNCTION ---
def process_and_stream_webcam():
    """
    This generator function reads from the webcam, runs detection, draws boxes, 
    sends data to the API, and yields frames for the web stream.
    """
    # --- MODIFICATION: Use WEBCAM_INDEX instead of VIDEO_PATH ---
    cap = cv2.VideoCapture(WEBCAM_INDEX)
    if not cap.isOpened():
        print(f"ERROR: Could not open webcam with index {WEBCAM_INDEX}.")
        return

    print("APP: Started webcam processing and streaming.")
    
    last_api_call_time = 0

    while True: # Loop indefinitely for a webcam stream
        success, frame = cap.read()
        if not success:
            print("APP: Failed to grab frame from webcam. Exiting.")
            break

        # --- Perform YOLO Detection ---
        results = model(frame, verbose=False)
        
        # --- Prepare Data for API (periodically) ---
        current_time = time.time()
        if (current_time - last_api_call_time) > API_CALL_INTERVAL:
            attentive_count, total_detected = 0, 0
            
            for box in results[0].boxes:
                if float(box.conf[0]) > CONFIDENCE_THRESHOLD:
                    total_detected += 1
                    class_name_from_model = model.names[int(box.cls[0])]
                    if class_name_from_model.lower() == 'attentive':
                        attentive_count += 1
            
            # --- MODIFICATION: Use current time for timestamp ---
            # Using a real timestamp is better for live feeds
            payload = {
                'timestamp': datetime.datetime.now().isoformat(), # e.g., '2023-10-27T10:30:00.123456'
                'attentiveness_percentage': round((attentive_count / total_detected) * 100) if total_detected > 0 else 0,
                'attentive_count': attentive_count,
                'total_detected': total_detected
            }
            try:
                requests.post(API_ENDPOINT, json=payload, timeout=1.0)
                print(f"APP: Sent API data: {payload}")
                last_api_call_time = current_time
            except requests.exceptions.RequestException as e:
                print(f"APP: API call failed. Error: {e}")

        # --- Draw Bounding Boxes on the Frame ---
        detections = results[0].boxes.data.cpu().numpy()
        for det in detections:
            x1, y1, x2, y2, conf, cls_id = det
            class_name = CLASS_NAMES.get(int(cls_id), 'Unknown')
            
            color = (0, 255, 0) if class_name.lower() == 'attentive' else (0, 0, 255)
            cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)), color, 2)
            label = f"{class_name}: {conf:.2f}"
            cv2.putText(frame, label, (int(x1), int(y1) - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.7, color, 2)

        # --- Encode and Yield Frame for Browser ---
        ret, buffer = cv2.imencode('.jpg', frame)
        if not ret:
            continue
            
        frame_bytes = buffer.tobytes()
        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')
        
    cap.release()
    print("APP: Webcam stream stopped.")

# --- 4. FLASK ROUTE ---
@app.route('/video_feed')
def video_feed():
    return Response(process_and_stream_webcam(), mimetype='multipart/x-mixed-replace; boundary=frame')

# --- 5. START SERVER ---
if __name__ == '__main__':
    print("APP: Starting Flask server...")
    print("Navigate to http://127.0.0.1:5001/video_feed in your browser.")
    app.run(host='0.0.0.0', port=5001, threaded=True)