from flask import Flask, request, jsonify
import face_recognition
import numpy as np

app = Flask(__name__)

def load_image(image_path):
    try:
        return face_recognition.load_image_file(image_path)
    except Exception as e:
        return []

@app.route('/face_encoding', methods=['POST'])
def face_encoding():
    image_path = request.json.get('image_path')
    image = load_image(image_path)
    face_locations = face_recognition.face_locations(image)
    if len(face_locations) > 0:
        encodings = face_recognition.face_encodings(image, face_locations)[0]
        return jsonify({'status': 'success', 'encodings': encodings.tolist()})
    else:
        return jsonify({'status': 'failure', 'error': []})

if __name__ == '__main__':
    app.run(host='127.0.0.1', port=5000)