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
   Version       : 1.0
   Created       : 18/03/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
*/

#include <Wire.h>

#define CHARGE1_RELAY_PIN       (22)
#define CHARGE2_RELAY_PIN       (23)
#define CHARGE3_RELAY_PIN       (24)
#define CHARGE4_RELAY_PIN       (25)

#define CURRENT_TYPE_DC         (1)
#define CURRENT_TYPE_AC         (0)

#define LED_STATE_READY_PIN     (30)
#define LED_STATE_ACT_PIN       (31)

#define CURRENTDC_SENSOR_PIN    (A0) // 54
#define CURRENTAC_SENSOR_PIN    (A1) // 55
#define VOLTAGEDC_SENSOR_PIN    (A2) // 56
#define TEMP_SENSOR_PIN         (A3) // 57
#define BRIGHTNESS_SENSOR_PIN   (A4) // 58
#define HUMIDITY_SENSOR_PIN     (A5) // 59

float VOLTAGEDC_RESISTOR1 = 30000.0;
float VOLTAGEDC_RESISTOR2 = 7500.0;
float VOLTAGEDC_REF_VOLTAGE = 5.0;

void setup() {
    pinMode(LED_STATE_READY_PIN, OUTPUT);
    pinMode(LED_STATE_ACT_PIN, OUTPUT);
    digitalWrite(LED_STATE_READY_PIN, LOW);
    digitalWrite(LED_STATE_ACT_PIN, LOW);
    
    for (int i = 22; i <= 25; i++) {
        pinMode(i, OUTPUT);
        digitalWrite(i, HIGH);
    }
    for (int i = 26; i <= 29; i++) pinMode(i, INPUT);

    digitalWrite(LED_STATE_READY_PIN, HIGH);
    Serial.begin(9600);
}

void loop() {

    digitalWrite(LED_STATE_ACT_PIN, LOW);
    
    // Capteur de Température
    float TEMP_SENSOR_VALUE = (analogRead(TEMP_SENSOR_PIN) * (5.0 / 1023.0 * 100.0));

    // Capteur d'Humidité
    float HUMIDITY_SENSOR_VALUE = ((analogRead(HUMIDITY_SENSOR_PIN)) * (100 - 10) / (1023) + 10);

    // Capteur de Luminosité
    float BRIGHTNESS_SENSOR_VALUE = 100 - (((analogRead(HUMIDITY_SENSOR_PIN)) * (100 - 15) / (1023) + 15));

    // Capteur de Courant DC
    float CURRENTDC_SENSOR_VALUE = getCurrent(CURRENT_TYPE_DC);

    // Capteur de Courant AC
    float CURRENTAC_SENSOR_VALUE = getCurrent(CURRENT_TYPE_AC);

    // Capteur de Tension DC
    float VOLTAGEDC_SENSOR_RAW = (analogRead(VOLTAGEDC_SENSOR_PIN) * VOLTAGEDC_REF_VOLTAGE) / 1024.0;
    float VOLTAGEDC_SENSOR_VALUE = VOLTAGEDC_SENSOR_RAW / (VOLTAGEDC_RESISTOR2 / (VOLTAGEDC_RESISTOR1 + VOLTAGEDC_RESISTOR2));

    
    // Communication avec le Raspberry Pi
    if (Serial.available() > 0) {
        digitalWrite(LED_STATE_ACT_PIN, HIGH);
        getChargeCommand();
        sendValue(TEMP_SENSOR_PIN, TEMP_SENSOR_VALUE); // Température
        delay(2);

        getChargeCommand();
        sendValue(HUMIDITY_SENSOR_PIN, HUMIDITY_SENSOR_VALUE); // Humidité
        delay(2);

        getChargeCommand();
        sendValue(BRIGHTNESS_SENSOR_PIN, BRIGHTNESS_SENSOR_VALUE); // BRIGHTNESS
        delay(2);

        getChargeCommand();
        sendValue(CURRENTDC_SENSOR_PIN, CURRENTDC_SENSOR_VALUE); // Courant DC
        delay(2);

        getChargeCommand();
        sendValue(CURRENTAC_SENSOR_PIN, CURRENTAC_SENSOR_VALUE); // Courant AC
        delay(2);

        getChargeCommand();
        sendValue(VOLTAGEDC_SENSOR_PIN, VOLTAGEDC_SENSOR_VALUE); // Tension DC
        delay(2);
    }
    digitalWrite(LED_STATE_ACT_PIN, LOW);
}

void sendValue(int packetid, float value) {
    if (value > 255) Serial.print("Error: Sensor ID " + String(packetid) + " Value cannot be greater than 255.\n");
    else if (value < -255) Serial.print("Error: Sensor ID " + String(packetid) + " Value cannot be lower than -255.\n");
    else Serial.print("setsensor " + String(packetid) + " " + String(value) + "\n");
}

void getChargeCommand() {
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

float getCurrent(bool type) {
    float voltage_raw;
    if(type == CURRENT_TYPE_DC) voltage_raw = (5.0 / 1023.0)*analogRead(CURRENTDC_SENSOR_PIN);
    else voltage_raw = (5.0 / 1023.0)*analogRead(CURRENTAC_SENSOR_PIN);
    
    float voltage =  voltage_raw - 2.5 + 0.012 ;
    float current = voltage / 0.066;
   
    if(abs(current) > 0.1) return current;
    else return 0.0;
}
