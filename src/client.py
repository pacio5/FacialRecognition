import face_recognition #require dlib
import numpy as np
import pymysql
from pymysql.cursors import DictCursor
import argparse
import cv2
import datetime
from headpose.detect import PoseEstimator
from pose_estimation import HeadMovement

# Configura la connessione al database MariaDB
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "root",
    "database": "facial_recognition"
}

# Connessione al database
def connect_to_db():
    return pymysql.connect(**db_config, cursorclass=DictCursor)

# Recupera tutti i volti presenti nel database
def get_all_known_faces():
    with connect_to_db() as connection:
        with connection.cursor() as cursor:
            sql = "SELECT * FROM authorized_faces"
            cursor.execute(sql)
            return cursor.fetchall()

# Registra un tentativo di accesso nel database
def log_access_attempt(authorized, authorized_face_id):
    attempted_at = datetime.datetime.now()  # Prendi l'ora corrente
    with connect_to_db() as connection:
        with connection.cursor() as cursor:
            # Inserisci il tentativo di accesso nel database
            if(authorized_face_id != None):
                sql = "INSERT INTO access_attempts (authorized, attempted_at, authorized_face_id) VALUES (%s, %s, %s)"
                cursor.execute(sql, (authorized, attempted_at, authorized_face_id))
            else:
                sql = "INSERT INTO access_attempts (authorized, attempted_at) VALUES (%s, %s)"
                cursor.execute(sql, (authorized, attempted_at))
            connection.commit()

def movement_check(video_path):
    video_capture = cv2.VideoCapture(args.video_path)
    fps = video_capture.get(cv2.CAP_PROP_FPS)
    head_movement = HeadMovement()
    est = PoseEstimator()

    position = "none"

    frame_counter = 0
    while video_capture.isOpened() and not position == "none":
        ret, frame = video_capture.read()

        if not ret:
            break

        if frame_counter % fps < 1:
            try:
                est.detect_landmarks(frame, plot=False)
                roll, pitch, yaw = est.pose_from_image(frame)
                position = head_movement.detect_movement(roll, pitch, yaw)
                print("Debug posizione")
                print(position)
            except ValueError:
                pass

        frame_counter += 1

    video_capture.release()
    return position


def recognize_face(video_path):
    # Apre il video
    video_capture = cv2.VideoCapture(video_path)
    fps = video_capture.get(cv2.CAP_PROP_FPS)

    # Carica i volti noti
    known_faces = get_all_known_faces()

    known_face_encodings = []
    known_face_names = []
    known_face_authorized = []
    known_face_ids = []

    for face in known_faces:
        known_face_names.append(face['name'])
        known_face_encodings.append(np.frombuffer(face['encoding']))
        known_face_authorized.append(face['is_authorized'])
        known_face_ids.append(face['id'])

    face_detected = False
    frame_count = 0

    while video_capture.isOpened() and not face_detected and frame_count < 4 * fps:
        # Legge il video frame per frame
        video_capture.set(cv2.CAP_PROP_POS_FRAMES, frame_count)
        ret, frame = video_capture.read()
        
        # Se il frame non è letto correttamente, esce dal ciclo
        if not ret:
            break

        # Trova tutte le facce e le codifiche del volto nel frame corrente del video
        face_locations = face_recognition.face_locations(frame)
        face_encodings = face_recognition.face_encodings(frame, face_locations)

        # Loop tra le facce rilevate nel frame
        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)

            name = ""
            authorized = 0
            face_id = 0

            if True in matches:
                first_match_index = matches.index(True)
                name = known_face_names[first_match_index]
                authorized = known_face_authorized[first_match_index]
                face_id = known_face_ids[first_match_index]

            if name != "":
                if authorized == '1':
                    print(f"Sono nel Welcome!")
                    # Se c'è il check della faccia verifico il movimento
                    position = movement_check(video_path)
                    if position != "none":
                        print(f"Welcome {name}!")
                        log_access_attempt(True, face_id)
                    else: 
                        print("Incorrect movement")
                else:
                    print(f"Sorry {name}, you are not authorized!")
                    log_access_attempt(False, face_id)
                face_detected = True

        frame_count += fps


    if face_detected == False:
        print(f"No match, unknown people!")
        log_access_attempt(False, None)


    video_capture.release()


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Recognize faces.')
    parser.add_argument('--video_path', required=True, help='Path to the video file.')

    args = parser.parse_args()

    recognize_face(args.video_path)