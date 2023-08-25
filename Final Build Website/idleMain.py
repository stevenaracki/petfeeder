import RPi.GPIO as GPIO
import time
import os
from mfrc522 import SimpleMFRC522
import mysql.connector
import datetime

def get_today_date():
    today = datetime.datetime.today()
    return today.strftime("%m/%d/%Y")
    
def feederIdle(date_column): 
    # Set up database connection
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="petsarefun",
        database="testDB"
        )
        
    cursor = mydb.cursor()

    # Set up SimpleMFRC522 reader
    reader = SimpleMFRC522()

    while True:
        # Scan for tags
        try:
            id, text = reader.read() #This command sits until a tag is read

            # Check if the ID is in the database
            cursor.execute('SELECT amountTotal, amountPortion, name, {date_column}, id FROM test WHERE id=?', (id, ))
            row = cursor.fetchone()

            if row is not None:
                # If the ID is found, return the matching entry's values for amountTotal, amountPortion, and amountRecord
                amountTotal = float(row[0]) #These row references will be different depending on the database setup
                amountPortion = float(row[1])
                amountRecord = float(row[3])
                return amountTotal, amountPortion, amountRecord, id
            else:
                # If the ID is not found, create a new entry in the database with default values and return the new entry's values
                cursor.execute('INSERT INTO test (id, Name, amountTotal, amountPortion, {0}) VALUES (%s, %s, %s, %s, %s)'.format(column_name),
                          (id, 'Blank', 10.0, 1.0, 1.0))
                mydb.commit()
                cursor.execute('SELECT * FROM test WHERE id=%s', (id,))
                row = cursor.fetchone()
                amountTotal = float(row[2])
                amountPortion = float(row[3])
                amountRecord = float(row[4])
                return amountTotal, amountPortion, amountRecord, id

        except KeyboardInterrupt:
            GPIO.cleanup()
            raise

        # Wait for a short time before scanning for another card
        time.sleep(0.1)
        
def addColumn(new_column_name): 
    # Adds a column to the selected table, this way we can have a pet Table with each row being a pet and have the first columns be amount, portion
    # then each column after that will be its own day. another function can reference this table, find a row with a matching ID value and update the column for the date created here
    # next command connects to the database
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="petsarefun",
        database="testDB"
        )
        
    cursor = mydb.cursor()
    
    # Check if a column with the same name already exists in the table
    cursor.execute("DESCRIBE test")
    existing_columns = [col[1] for col in cursor.fetchall()]
    if new_column_name in existing_columns:
        print(f"Column {new_column_name} already exists in table test")
        # Close the connection and return without adding the column
        mydb.close()
        return

    # add a new column to the table if the column exists
    cursor.execute(f"ALTER TABLE test ADD COLUMN {new_column_name} REAL")

    # commit the changes
    mydb.commit()
    

def updatePetRecord(column_name, pet_id, amount):
    # connect to the database
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="petsarefun",
        database="testDB"
        )
        
    cursor = mydb.cursor()
    
    
    # Check if the column exists, and call the addColumn function if not
    cursor.execute("DESCRIBE test")
    existing_columns = [col[1] for col in cursor.fetchall()]
    if column_name not in existing_columns:
        addColumn(column_name)
     
    
    # execute a SELECT statement to find the value of a colum of an entry in the table that has a matching id value
    cursor.execute(f"SELECT {column_name} FROM test WHERE petId = %s", (pet_id,))
    result = cursor.fetchone() #store the matching result
   
    # update the value
    if result is not None:
        current_value = result[0] # assign the current value
        new_value = current_value + amount #calculate new value
        cursor.execute(f"UPDATE test SET {column_name} = %s WHERE petId = %s", (new_value, pet_id)) # update the entry with the new value
        mydb.commit()
        updated = True
    else:
        updated = False

    # close the connection
    mydb.close()

    # return whether the record was updated or not
    return updated	
	
def motor(amount, portion, record):
	in1 = 17
	in2 = 18
	in3 = 27
	in4 = 22
	
	step_sleep = 0.002
	
    if amount < record: #If the amount total is less than the recorded amount exit with 0.0 dispensed
        return 0.0
    
	step_input = float(portion)
	step_ref = step_input*2048 #Convert to steps
	step_count = int(step_ref) #Convert to int so it can be referenced later
	os.system('clear')
	print("Turning") 
	
	direction = False
	
	step_sequence = [[1,0,0,1],
			[1,1,0,0],
			[0,1,1,0],
			[0,0,1,1]]


	GPIO.cleanup()
	GPIO.setmode( GPIO.BCM )
	GPIO.setup( in1, GPIO.OUT )
	GPIO.setup( in2, GPIO.OUT )
	GPIO.setup( in3, GPIO.OUT )
	GPIO.setup( in4, GPIO.OUT )
	
	
	GPIO.output( in1, GPIO.LOW )
	GPIO.output( in2, GPIO.LOW )
	GPIO.output( in3, GPIO.LOW )
	GPIO.output( in4, GPIO.LOW )
	
	motor_pins = [in1, in2, in3, in4]
	motor_step_counter = 0 ;
	

	try:
		i = 0
		for i in range(step_count):
			for pin in range(0, len(motor_pins)):
				GPIO.output( motor_pins[pin], step_sequence[motor_step_counter][pin] )
			if direction==True:
				motor_step_counter = (motor_step_counter - 1) % 4
			elif direction ==False:
				motor_step_counter = (motor_step_counter + 1) % 4
			else:
				print("You broke something bruva")
				exit( 1 )
			time.sleep( step_sleep )
			
	except KeyboardInterrupt:
		GPIO.cleanup()
			
	GPIO.cleanup()
	return portion

def main():
    GPIO.setwarnings(False)
    print("Ready to scan in one second. The system will stay idle indefinitely...")
    print("Press ctrl + c to exit anytime....")
    time.sleep(1)
    os.system('clear')


    while True:
        database_file = 'testDB.db'
        table_name = 'test'    
        columnUpdate = get_today_date() #Fetches today's date in a string so the update record can find the column to update
        petTotal, petPortion, petRecord, petID = feederIdle(columnUpdate) #This calls the Identification function and assigns returned tuple values to each variable    
        foodDispensed = motor(petTotal, petPortion, petRecord) #Motor executes
        updatePetRecord(columnUpdate, petID, foodDispensed) #Updates database record and columns if needed as well
    
	
    print("Goodbye!")
    
main()
