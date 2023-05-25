# Require matplotlib
from headpose.detect import PoseEstimator

class HeadMovement:
    def __init__(self):
        self.prev_roll = None
        self.prev_pitch = None
        self.prev_yaw = None
        self.blocked = False

    def detect_movement(self, roll, pitch, yaw):
        roll_change = pitch_change = yaw_change = None
        if self.prev_roll is not None and self.prev_pitch is not None and self.prev_yaw is not None:
            roll_change = roll - self.prev_roll
            pitch_change = pitch - self.prev_pitch
            yaw_change = yaw - self.prev_yaw

            position = "none"

            if not self.blocked:
                if yaw_change > 10:
                    position = "left"
                    self.blocked = True
                elif yaw_change < -10:
                    position = "right"
                    self.blocked = True
                elif pitch_change > 10:
                    position = "up"
                    print("Head moved up")
                    self.blocked = True
                elif pitch_change < -10:
                    position = "down"
                    self.blocked = True
        return position