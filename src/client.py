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

def movement_check(frames):
    fps = frames.get(cv2.CAP_PROP_FPS)
    head_movement = HeadMovement()
    est = PoseEstimator()

    position = "none"

    frame_count = 0
    while frame_count < 4 * fps and not position == "none":
        frames_to_skip = int(fps)
        if frame_count % frames_to_skip == 0:
            frame = frames[frame_count]
            try:
                est.detect_landmarks(frame, plot=False)
                roll, pitch, yaw = est.pose_from_image(frame)
                position = head_movement.detect_movement(roll, pitch, yaw)
            except ValueError:
                pass

        frame_count += 1
    return position


def recognize_face(frames, direction):
    frames_to_skip = 30  # Assumiamo 30 fps, quindi analizziamo un frame per ogni secondo di video

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

    authorized = 0
    message = ""

    while frame_count < len(frames) and not face_detected:
        # Analizza solo ogni N-esimo frame
        if frame_count % frames_to_skip == 0:
            frame = frames[frame_count]

            # Trova tutte le facce e le codifiche del volto nel frame corrente del video
            face_locations = face_recognition.face_locations(frame)
            face_encodings = face_recognition.face_encodings(frame, face_locations)

            # Loop tra le facce rilevate nel frame
            for face_encoding in face_encodings:
                matches = face_recognition.compare_faces(known_face_encodings, face_encoding)

                name = ""
                face_id = 0

                if True in matches:
                    first_match_index = matches.index(True)
                    name = known_face_names[first_match_index]
                    authorized = known_face_authorized[first_match_index]
                    face_id = known_face_ids[first_match_index]

                if name != "":
                    if authorized == '1':
                    # Se c'è il check della faccia verifico il movimento
                        position = direction  # Here I assume that 'direction' has the same value as your 'position'
                        if position != "none":
                            message = f"Welcome {name}!"
                            log_access_attempt(True, face_id)
                        else: 
                            message = f"Hello, {name}. Incorrect movement!"
                            authorized = 0
                    else:
                        message = f"Sorry {name}, you are not authorized!"
                        log_access_attempt(False, face_id)
                    face_detected = True
    
        frame_count += 1  

    if face_detected == False:
        message = "No match, unknown people!"
        log_access_attempt(False, None)

    return {"message": message, "is_authorized": authorized}
