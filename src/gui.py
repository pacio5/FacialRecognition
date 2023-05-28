# Import necessary libraries
import cv2
import sys
import random
from PyQt5.QtWidgets import QApplication, QWidget, QPushButton, QVBoxLayout, QLabel
from PyQt5.QtGui import QImage, QPixmap
from PyQt5.QtCore import QTimer, Qt
from client import recognize_face # Function for face recognition

# Define the application class which inherits from QWidget
class App(QWidget):
    def __init__(self):
        super().__init__()
        # Initializing webcam
        self.webcam = cv2.VideoCapture(0)
        # Initializing a timer
        self.timer = QTimer()
        self.record_timer = QTimer()
        self.record_timer.setInterval(4000)  # Set delay to 4 seconds
        self.record_timer.timeout.connect(self.stop_recording)

        self.delay_timer = QTimer()  # New delay timer
        self.delay_timer.setSingleShot(True)  # Ensure it only runs once each time it's started
        self.delay_timer.timeout.connect(self.begin_recording)  # Begin recording when timer times out

        self.timer.timeout.connect(self.update_frame)
        self.timer.start(20)  # Start the timer immediately to show webcam feed

        # Setting up the layout for the application
        self.layout = QVBoxLayout()
        self.setLayout(self.layout)

        self.start_button = QPushButton("Login")
        self.start_button.clicked.connect(self.prepare_recording) # Connect the button click to the prepare_recording method

        self.label = QLabel()
        
        # Setting up the instructions
        self.instructions = {
            "up": "Look at the camera then move your head upwards",
            "down": "Look at the camera then move your head downwards",
            "left": "Look at the camera then move your head to the left",
            "right": "Look at the camera then move your head to the right",
        }

        # Setting up the instruction label
        self.instruction_label = QLabel()
        self.instruction_label.setAlignment(Qt.AlignCenter)  # Align text to center
        self.instruction_label.setStyleSheet("color: red; font-size: 20px")  # Change color to red and increase font size
        self.layout.addWidget(self.start_button)
        self.layout.addWidget(self.instruction_label)
        self.layout.addWidget(self.label)

        # Set the size of the widget to the size of the webcam feed plus some extra space
        width = int(self.webcam.get(cv2.CAP_PROP_FRAME_WIDTH))
        height = int(self.webcam.get(cv2.CAP_PROP_FRAME_HEIGHT)) + 100  # Add some extra space for buttons and instructions
        self.resize(width, height)

        self.recording_frames = []  # List to store frames during recording
        self.random_key = None  # Store random key
   
    # Method to prepare for recording
    def prepare_recording(self):
        if not self.record_timer.isActive():
            self.start_button.setText("Preparing...")
            self.random_key = random.choice(list(self.instructions.keys()))  # Store the random key
            self.instruction_label.setText(self.instructions[self.random_key])
            self.delay_timer.start(3000)  # Start the delay timer for 3 seconds

    # Method to begin recording
    def begin_recording(self):
        self.record_timer.start()  # Start recording timer
        self.start_button.setText("Checking")

    # Method to stop recording
    def stop_recording(self):
        if self.record_timer.isActive():
            self.record_timer.stop()
            self.start_button.setText("Login")
            result = recognize_face(self.recording_frames, self.instructions[self.random_key])

            # Print message
            self.instruction_label.setStyleSheet("QLabel { color : green; }" if result["is_authorized"] else "QLabel { color : red; }")
            self.instruction_label.setText(result["message"])
            self.recording_frames = []  # Clear the list for next recording
    
    # Method to update the frame shown in the label
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
    
    # Method to stop the timer when the application is closed
    def closeEvent(self, event):
        self.timer.stop()

if __name__ == "__main__":
    app = QApplication(sys.argv)
    window = App()
    window.setWindowTitle('Facial Recognition')
    window.show()
    sys.exit(app.exec_())
