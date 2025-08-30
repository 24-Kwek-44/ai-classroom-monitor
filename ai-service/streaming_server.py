# File: streaming_server.py
from flask import Flask, Response
import cv2
from ultralytics import YOLO
import requests
import time
import json

# --- 1. CONFIGURATION ---
MODEL_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\training_results\final_balanced_run\weights\best.pt"
VIDEO_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\classroom_video_3.mp4"
API_ENDPOINT = "http://127.0.0.1:8000/api_receiver.php"
CONFIDENCE_THRESHOLD = 0.40
API_CALL_INTERVAL = 2

# --- 2. INITIALIZATION ---
app = Flask(__name__)
model = YOLO(MODEL_PATH)

def generate_frames():
    """Reads frames, runs detection, sends data, and yields the frame for streaming."""
    
    video_capture = cv2.VideoCapture(VIDEO_PATH)
    if not video_capture.isOpened():
        print("Error: Could not open video file.")
        return

    last_api_call_time = 0
    frame_count = 0
    fps = video_capture.get(cv2.CAP_PROP_FPS)

    while True:
        success, frame = video_capture.read()
        if not success:
            print("Video file finished.")
            break
        
        frame_count += 1
        
        # --- Run YOLO detection ---
        results = model(frame, verbose=False)
        annotated_frame = results[0].plot()

        # --- Send data to Laravel periodically ---
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
                # We send the request and set a small timeout (e.g., 0.5 seconds)
                # This prevents the video stream from freezing if the Laravel server is slow
                requests.post(API_ENDPOINT, json=payload, timeout=0.5)
                print(f"SENT DATA: {payload}")
                last_api_call_time = current_time
            except requests.exceptions.RequestException:
                # If it fails, we just print a message and continue without crashing
                print("API CALL FAILED: Could not connect to Laravel server.")


        # --- Yield the processed frame for the browser ---
        ret, buffer = cv2.imencode('.jpg', annotated_frame)
        if ret:
            frame_bytes = buffer.tobytes()
            yield (b'--frame\r\n'
                   b'Content-Type: image/jpeg\r\n\r\n' + frame_bytes + b'\r\n')
    
    video_capture.release()

@app.route('/video_feed')
def video_feed():
    return Response(generate_frames(), mimetype='multipart/x-mixed-replace; boundary=frame')

if __name__ == '__main__':
    print("Starting Flask streaming server at http://127.0.0.1:5001/video_feed")
    # Setting threaded=True is important for Flask to handle the stream and API calls
    app.run(host='0.0.0.0', port=5001, threaded=True)