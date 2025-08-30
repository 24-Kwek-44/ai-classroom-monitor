# File: detector_with_stream.py
import cv2
from ultralytics import YOLO
import requests
import time
import json
import asyncio
import websockets
import threading

# --- 1. CONFIGURATION ---
MODEL_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\training_results\final_balanced_run\weights\best.pt"
VIDEO_PATH = r"D:\UTAR\SEM 3\PROJECT II\ai-classroom-monitor\ai-service\classroom_video_3.mp4"
API_ENDPOINT = "http://127.0.0.1:8000/api_receiver.php"
CONFIDENCE_THRESHOLD = 0.40
API_CALL_INTERVAL = 2

# --- 2. WEBSOCKET SETUP ---
connected_clients = set()

# --- THIS IS THE FIX ---
# The handler function only takes one argument: 'websocket'
async def video_stream_handler(websocket):
    print(f"Client connected: {websocket.remote_address}")
    connected_clients.add(websocket)
    try:
        await websocket.wait_closed()
    finally:
        print(f"Client disconnected: {websocket.remote_address}")
        connected_clients.remove(websocket)

async def start_server():
    async with websockets.serve(video_stream_handler, "0.0.0.0", 8001):
        print("WebSocket server started on port 8001.")
        await asyncio.Future()

def run_websocket_server_in_thread():
    # This is a fix to ensure the asyncio loop is created correctly within the new thread
    loop = asyncio.new_event_loop()
    asyncio.set_event_loop(loop)
    loop.run_until_complete(start_server())
    loop.close()

# --- 3. MAIN DETECTION AND STREAMING LOGIC ---
def run_detection_and_api():
    model = YOLO(MODEL_PATH)
    cap = cv2.VideoCapture(VIDEO_PATH)
    if not cap.isOpened(): return

    print("Starting detection and streaming...")
    last_api_call_time = 0
    frame_count = 0
    fps = cap.get(cv2.CAP_PROP_FPS)

    while cap.isOpened():
        success, frame = cap.read()
        if not success: break
        
        frame_count += 1
        
        results = model(frame, verbose=False)
        annotated_frame = results[0].plot()

        # --- Stream frame to browser ---
        if connected_clients:
            ret, buffer = cv2.imencode('.jpg', annotated_frame)
            if ret:
                frame_bytes = buffer.tobytes()
                # Use asyncio.run() to send message from a synchronous function
                # This is a simplified way to interact with the running event loop
                tasks = [client.send(frame_bytes) for client in connected_clients]
                if tasks:
                    try:
                        # Find the running event loop and run the tasks on it
                        loop = asyncio.get_event_loop()
                        if loop.is_running():
                           asyncio.run_coroutine_threadsafe(asyncio.gather(*tasks), loop)
                    except RuntimeError: # If no event loop is running in the main thread
                        pass

        # --- Send data to Laravel ---
        current_time = time.time()
        if (current_time - last_api_call_time) > API_CALL_INTERVAL:
            attentive_count, total_detected = 0, 0
            for box in results[0].boxes:
                if float(box.conf[0]) > CONFIDENCE_THRESHOLD:
                    total_detected += 1
                    if model.names[int(box.cls[0])].lower() == 'attentive': attentive_count += 1
            
            payload = {
                'timestamp': round(frame_count / fps if fps > 0 else 0),
                'attentiveness_percentage': round((attentive_count / total_detected) * 100) if total_detected > 0 else 0,
                'attentive_count': attentive_count, 'total_detected': total_detected
            }
            threading.Thread(target=lambda: requests.post(API_ENDPOINT, json=payload)).start()
            print(f"SENT DATA: {payload}")
            last_api_call_time = current_time

    cap.release()
    print("Detection finished.")

# --- 4. START EVERYTHING ---
if __name__ == '__main__':
    server_thread = threading.Thread(target=run_websocket_server_in_thread, daemon=True)
    server_thread.start()
    time.sleep(2)
    run_detection_and_api()