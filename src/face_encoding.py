# Import the necessary libraries: Flask for the web server and face_recognition for facial recognition.
from flask import Flask, request, jsonify
import face_recognition
import numpy as np 

# Create an instance of the Flask application.
app = Flask(__name__)

# Define a function to load an image from a specified file path.
def load_image(image_path):
    try:
        return face_recognition.load_image_file(image_path)
    except Exception as e:
        # If an error occurs, returns an empty array.
        return []

# Define a route for the server to respond to POST requests to the URL '/face_encoding'.
@app.route('/face_encoding', methods=['POST'])
def face_encoding():
    # Extracts the image path from the body of the JSON request.
    image_path = request.json.get('image_path')
    # Load the image using the function defined above.
    image = load_image(image_path)
    # Find the positions of faces in the image using the face_recognition library.
    face_locations = face_recognition.face_locations(image)

    if len(face_locations) > 0:
        # Calculates face encodings for the first face found in the image.
        encodings = face_recognition.face_encodings(image, face_locations)[0]
        return jsonify({'status': 'success', 'encodings': encodings.tolist()})
    else:
        return jsonify({'status': 'failure', 'error': []})

# If this script is executed directly (not imported from another script).
if __name__ == '__main__':
    # Start the Flask server listening on the local address (127.0.0.1) at port 5000.
    app.run(host='127.0.0.1', port=5000)