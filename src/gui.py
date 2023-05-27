import cv2
import sys
import random
from PyQt5.QtWidgets import QApplication, QWidget, QPushButton, QVBoxLayout, QLabel
from PyQt5.QtGui import QImage, QPixmap
from PyQt5.QtCore import QTimer, Qt

class App(QWidget):
    def __init__(self):
        super().__init__()
        self.webcam = cv2.VideoCapture(0)
        self.timer = QTimer()
        self.record_timer = QTimer()
        self.record_timer.setInterval(4000)  # Set record length to 4 seconds
        self.record_timer.timeout.connect(self.stop_recording)

        self.timer.timeout.connect(self.update_frame)
        self.timer.start(20)  # Start the timer immediately to show webcam feed

        self.layout = QVBoxLayout()
        self.setLayout(self.layout)

        self.start_button = QPushButton("Start Recording")
        self.start_button.clicked.connect(self.start_recording)

        self.label = QLabel()
        
        # Set up the instructions
        self.instructions = {
            "up": "Look at the camera then move your head upwards",
            "down": "Look at the camera then move your head downwards",
            "left": "Look at the camera then move your head to the left",
            "right": "Look at the camera then move your head to the right",
        }

        self.instruction_label = QLabel()
        self.instruction_label.setAlignment(Qt.AlignCenter)  # Align text to center
        self.instruction_label.setStyleSheet("color: red; font-size: 20px")  # Change color to red and increase font size
        self.layout.addWidget(self.start_button)
        self.layout.addWidget(self.instruction_label)
        self.layout.addWidget(self.label)

        width = int(self.webcam.get(cv2.CAP_PROP_FRAME_WIDTH))
        height = int(self.webcam.get(cv2.CAP_PROP_FRAME_HEIGHT)) + 100  # Add some extra space for buttons and instructions
        self.resize(width, height)

        self.recording_frames = []  # List to store frames during recording
        self.random_key = None  # Store random key

    def start_recording(self):
        if not self.record_timer.isActive():
            self.record_timer.start()  # Start recording timer
            self.start_button.setText("Recording...")
            self.random_key = random.choice(list(self.instructions.keys()))  # Store the random key
            self.instruction_label.setText(self.instructions[self.random_key])

    def stop_recording(self):
        if self.record_timer.isActive():
            self.record_timer.stop()
            self.start_button.setText("Start Recording")
            result = recognize_face(self.frames, self.directions[self.random_key])

            # Stampa il messaggio
            self.label.setStyleSheet("QLabel { color : green; }" if result["is_authorized"] else "QLabel { color : red; }")
            self.label.setText(result["message"])
            self.recording_frames = []  # Clear the list for next recording

    def update_frame(self):
        ret, frame = self.webcam.read()
        if ret:
            image = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
            height, width, channel = image.shape
            step = channel * width
            qimg = QImage(image.data, width, height, step, QImage.Format_RGB888)
            self.label.setPixmap(QPixmap.fromImage(qimg))
            
            # Store frame if recording
            if self.record_timer.isActive():
                self.recording_frames.append(frame)
        else:
            print("Cannot receive frame.")

    def closeEvent(self, event):
        self.timer.stop()

if __name__ == "__main__":
    app = QApplication(sys.argv)
    window = App()
    window.setWindowTitle('Webcam Recorder')
    window.show()
    sys.exit(app.exec_())

def recognize_face(frames, direction):
        pass