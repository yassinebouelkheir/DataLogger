/**
   Copyright (c) 2022 Data Logger

   This program is free software: you can redistribute it and/or modify it under the terms of the
   GNU General Public License as published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
   even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   General Public License for more details.

   You should have received a copy of the GNU General Public License along with this program.
   If not, see <http://www.gnu.org/licenses/>.
*/

/*
   ScriptName    : PFE.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 18/03/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
*/

#define LED_STATE_READY_PIN     (2)
#define LED_STATE_ACT_PIN       (3)


#define CURRENTDC_SENSOR_ID     (1)
#define VOLTAGEDC_SENSOR_ID     (2)

#define CURRENTAC_SENSOR_ID     (3)
#define VOLTAGEAC_SENSOR_ID     (4)

#define TEMPERATURE1_SENSOR_ID  (5)
#define TEMPERATURE2_SENSOR_ID  (6)

#define BRIGHTNESS_SENSOR_ID    (7)
#define HUMIDITY_SENSOR_ID      (8)
#define WINDMETER_SENSOR_ID     (9)


#define CHARGE1_RELAY_ID        (1)
#define CHARGE2_RELAY_ID        (2)
#define CHARGE3_RELAY_ID        (3)
#define CHARGE4_RELAY_ID        (4)
#define CHARGE5_RELAY_ID        (5)
#define CHARGE6_RELAY_ID        (6)
#define CHARGE7_RELAY_ID        (7)
#define CHARGE8_RELAY_ID        (8)


void setup() 
{
    pinMode(LED_STATE_READY_PIN, OUTPUT);
    pinMode(LED_STATE_ACT_PIN, OUTPUT);
    digitalWrite(LED_STATE_ACT_PIN, LOW);

    digitalWrite(LED_STATE_READY_PIN, HIGH);
    Serial.begin(9600);
}

void loop() 
{
    digitalWrite(LED_STATE_ACT_PIN, LOW);
    
    // Capteur de Température
    float TEMP1_SENSOR_VALUE = 1.0;

    // Capteur de Température
    float TEMP2_SENSOR_VALUE = 2.0;

    // Capteur d'Humidité
    float HUMIDITY_SENSOR_VALUE = 3.0;

    // Capteur de Luminosité
    float BRIGHTNESS_SENSOR_VALUE = 4.0;

    // Capteur de Courant DC
    float CURRENTDC_SENSOR_VALUE = 5.0;

    // Capteur de Courant AC
    float CURRENTAC_SENSOR_VALUE = 6.0;

    // Capteur de Tension DC
    float VOLTAGEDC_SENSOR_VALUE = 7.0;

    // Capteur de Tension AC
    float VOLTAGEAC_SENSOR_VALUE = 8.0;

    // Capteur de Vitesse du vent
    float WINDSPEED_SENSOR_VALUE = 9.0;


    // Communication avec le Raspberry Pi
    if (Serial.available() > 0) 
    {
        digitalWrite(LED_STATE_ACT_PIN, HIGH);

        getChargeCommand();
        sendValue(TEMPERATURE1_SENSOR_ID, TEMP1_SENSOR_VALUE); // Température
        delay(0.75);

        getChargeCommand();
        sendValue(TEMPERATURE2_SENSOR_ID, TEMP2_SENSOR_VALUE); // Température
        delay(0.75);

        getChargeCommand();
        sendValue(HUMIDITY_SENSOR_ID, HUMIDITY_SENSOR_VALUE); // Humidité
        delay(0.75);

        getChargeCommand();
        sendValue(BRIGHTNESS_SENSOR_ID, BRIGHTNESS_SENSOR_VALUE); // Luminosité
        delay(0.75);

        getChargeCommand();
        sendValue(CURRENTDC_SENSOR_ID, CURRENTDC_SENSOR_VALUE); // Courant DC
        delay(0.75);

        getChargeCommand();
        sendValue(CURRENTAC_SENSOR_ID, CURRENTAC_SENSOR_VALUE); // Courant AC
        delay(0.75);

        getChargeCommand();
        sendValue(VOLTAGEDC_SENSOR_ID, VOLTAGEDC_SENSOR_VALUE); // Tension DC
        delay(0.75);

        getChargeCommand();
        sendValue(VOLTAGEAC_SENSOR_ID, VOLTAGEDC_SENSOR_VALUE); // Tension AC
        delay(0.75);

        getChargeCommand();
        sendValue(WINDMETER_SENSOR_ID, WINDSPEED_SENSOR_VALUE); // Vitesse du vent
        delay(0.75);
    }
    digitalWrite(LED_STATE_ACT_PIN, LOW);
}

void sendValue(int packetid, float value) 
{
    if (value > 255) Serial.print("Error: Sensor ID " + String(packetid) + " Value cannot be greater than 255.\n");
    else if (value < -255) Serial.print("Error: Sensor ID " + String(packetid) + " Value cannot be lower than -255.\n");
    else Serial.print("setsensor " + String(packetid) + " " + String(value) + "\n");
}

void getChargeCommand() 
{
    String Buff[10];
    int StringCount = 0;
    String data = Serial.readStringUntil('\n');
    if (data.length() > 1) {
        int id, value;
        while (data.length() > 0) {
            int index = data.indexOf(' ');
            if (index == -1) {
                Buff[StringCount++] = data;
                break;
            } else {
                Buff[StringCount++] = data.substring(0, index);
                data = data.substring(index + 1);
            }
        }
        if (id > 7) Serial.print("Error: Charge ID cannot be greater than 7.\n");
        else if (id < 0) Serial.print("Error: Charge ID cannot be lower than 0.\n");
        else if (value > 1) Serial.print("Error: Charge ID " + String(Buff[1]) + " Value cannot be greater than 1.\n");
        else if (value < 0) Serial.print("Error: Charge ID " + String(Buff[1]) + " Value cannot be lower than 0.\n");
        else {
          digitalWrite(Buff[1].toInt(), bool(!Buff[2].toInt()));
        }
    }
}