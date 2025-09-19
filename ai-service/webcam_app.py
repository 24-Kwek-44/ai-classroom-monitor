# File: webcam_app.py
from flask import Flask, Response
import cv2
import requests
import time
import json
from ultralytics import YOLO
import datetime  # For ISO-format timestamps in API payloads

# ==========================================================
# 1. UNIFIED CONFIGURATION
# Purpose: Centralize all key parameters (webcam, model, API).
# ==========================================================
WEBCAM_INDEX = 1   # Webcam device index (0 = default cam, 1 = external cam)
MODEL_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\training_results\final_balanced_run\weights\best.pt"

# API Configuration
API_ENDPOINT = "http://127.0.0.1:8000/api_receiver.php"
API_CALL_INTERVAL = 2  # Minimum seconds between API calls

# Model Configuration
CONFIDENCE_THRESHOLD = 0
CLASS_NAMES = {0: 'Attentive', 1: 'Not-Attentive'} 

# ==========================================================
# 2. INITIALIZATION
# Purpose: Create Flask app and load YOLO model.
# ==========================================================
app = Flask(__name__)
model = YOLO(MODEL_PATH)
print("APP: YOLO Model loaded successfully.")

# ==========================================================
# 3. CORE PROCESSING AND STREAMING FUNCTION
# Purpose: Read webcam frames, run YOLO detection, send API
#          updates, draw bounding boxes, and stream video.
# ==========================================================
def process_and_stream_webcam():
    """
    Generator function for Flask streaming route.
    Steps:
      1. Open webcam.
      2. Run YOLO detection per frame.
      3. Periodically send attentiveness data to API.
      4. Draw bounding boxes with class labels.
      5. Encode and yield frames for browser streaming.
    """
    # --- Open Webcam ---
    cap = cv2.VideoCapture(WEBCAM_INDEX)
    if not cap.isOpened():
        print(f"ERROR: Could not open webcam with index {WEBCAM_INDEX}.")
        return

    print("APP: Started webcam processing and streaming.")
    
    last_api_call_time = 0  # Track last API call time for throttling

    while True:  # Infinite loop until stream is stopped
        success, frame = cap.read()
        if not success:
            print("APP: Failed to grab frame from webcam. Exiting.")
            break

        # --- Run YOLO Detection on Current Frame ---
        results = model(frame, verbose=False)
        
        # --- Prepare and Send API Data (every API_CALL_INTERVAL) ---
        current_time = time.time()
        if (current_time - last_api_call_time) > API_CALL_INTERVAL:
            attentive_count, total_detected = 0, 0
            
            # Loop over all detections
            for box in results[0].boxes:
                if float(box.conf[0]) > CONFIDENCE_THRESHOLD:
                    total_detected += 1
                    class_name_from_model = model.names[int(box.cls[0])]
                    if class_name_from_model.lower() == 'attentive':
                        attentive_count += 1
            
            # Build JSON payload for API
            payload = {
                'timestamp': datetime.datetime.now().isoformat(),
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

        # --- Draw Bounding Boxes with Class Labels ---
        detections = results[0].boxes.data.cpu().numpy()
        for det in detections:
            x1, y1, x2, y2, conf, cls_id = det
            class_name = CLASS_NAMES.get(int(cls_id), 'Unknown')
            
            # Green for Attentive, Red for Not-Attentive
            color = (0, 255, 0) if class_name.lower() == 'attentive' else (0, 0, 255)
            cv2.rectangle(frame, (int(x1), int(y1)), (int(x2), int(y2)), color, 2)
            label = f"{class_name}: {conf:.2f}"
            cv2.putText(frame, label, (int(x1), int(y1) - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.7, color, 2)

        # --- Encode Frame and Yield for Flask Streaming ---
        ret, buffer = cv2.imencode('.jpg', frame)
        if not ret:
            continue
            
        frame_bytes = buffer.tobytes()
        yield (b'--frame\r\n'
            b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')
        
    cap.release()
    print("APP: Webcam stream stopped.")

# ==========================================================
# 4. FLASK ROUTE
# Purpose: Expose video stream at /video_feed endpoint.
# ==========================================================
@app.route('/video_feed')
def video_feed():
    return Response(
        process_and_stream_webcam(), 
        mimetype='multipart/x-mixed-replace; boundary=frame'
    )

# ==========================================================
# 5. START SERVER
# Purpose: Launch Flask app for browser-based video stream.
# ==========================================================
if __name__ == '__main__':
    print("APP: Starting Flask server...")
    print("Navigate to http://127.0.0.1:5001/video_feed in your browser.")
    app.run(host='0.0.0.0', port=5001, threaded=True)
