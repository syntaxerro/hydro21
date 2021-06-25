import grove_i2c_motor_driver
import time
import sys

try:
	# You can initialize with a different address too: grove_i2c_motor_driver.motor_driver(address=0x0a)
    m= grove_i2c_motor_driver.motor_driver()

    m.MotorDirectionSet(0b1010)	#"0b1010" defines the output polarity, "10" means the M+ is "positive" while the M- is "negtive"
   # m.MotorDirectionSet(0b0101)	#0b0101  Rotating in the opposite direction

	#FORWARD
    print("Changing pump speed from "+sys.argv[1]+" to "+sys.argv[2])
    if(int(sys.argv[1]) < int(sys.argv[2])):
	    for i in range(int(sys.argv[1]), int(sys.argv[2])+1):
	        m.MotorSpeedSetAB(i,0)
	        time.sleep(.05)
    else:
        for i in range(int(sys.argv[1]), int(sys.argv[2])-1, -1):
            m.MotorSpeedSetAB(i,0)
            time.sleep(.05)
except IOError:
	print("Unable to find the motor driver, check the addrees and press reset on the motor driver and try again")
