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

#define PACKET_TYPE_SENSOR      (0)
#define PACKET_TYPE_CHARGE      (1)

#define CHARGE1_RELAY_PIN       (22)
#define CHARGE2_RELAY_PIN       (23)
#define CHARGE3_RELAY_PIN       (24)
#define CHARGE4_RELAY_PIN       (25)

#define COMMAND1_CHARGE_PIN     (26)
#define COMMAND2_CHARGE_PIN     (27)
#define COMMAND3_CHARGE_PIN     (28)
#define COMMAND4_CHARGE_PIN     (29)

#define LED_STATE_READY_PIN     (30)
#define LED_STATE_ACT_PIN       (31)

#define CURRENTDC_SENSOR_PIN    (A0) // 54
#define CURRENTAC_SENSOR_PIN    (A1) // 55
#define VOLTAGEDC_SENSOR_PIN    (A2) // 56
#define TEMP_SENSOR_PIN         (A3) // 57
#define BRIGHTNESS_SENSOR_PIN   (A4) // 58
#define HUMIDITY_SENSOR_PIN     (A5) // 59

#define MEASURED_VCC            (4.70)

#define ACS758_SENSITIVITY      (40e-3)    
#define ACS758_NOISE            (10e-3)   
#define ACS758_OFFSET_LIM       (35e-3)    
#define MAINS_VOLTS_RMS         (220)    
#define V_PER_LSB               (MEASURED_VCC/1024.0)
#define ACS758_NOISE_LSB        (ACS758_NOISE/V_PER_LSB)
#define MIN_LSB                 (ACS758_NOISE_LSB*1.5) 
#define VOLTAGEAC_CONSTANT_EFF  (220)

static int ACS758_OFFSET = 512;

float VOLTAGEDC_RESISTOR1 = 30000.0;
float VOLTAGEDC_RESISTOR2 = 7500.0;
float VOLTAGEDC_REF_VOLTAGE = 5.0;

unsigned long COMMAND_TIMEOUT_TIME = 700;
unsigned long COMMAND_TIMEOUT_LAST_TIME = 0;

bool chargeState[26];

void setup() {
    pinMode(LED_STATE_READY_PIN, OUTPUT);
    pinMode(LED_STATE_ACT_PIN, OUTPUT);
    digitalWrite(LED_STATE_READY_PIN, LOW);
    digitalWrite(LED_STATE_ACT_PIN, LOW);
    
    for (int i = 22; i <= 25; i++) {
        pinMode(i, OUTPUT);
        digitalWrite(i, HIGH);
        chargeState[i] = false;
    }
    for (int i = 26; i <= 29; i++) pinMode(i, INPUT);

    ACS758_GET_OFFSET();
    digitalWrite(LED_STATE_READY_PIN, HIGH);
    Serial.begin(9600);
}

void loop() {
    if(millis() < 500) COMMAND_TIMEOUT_LAST_TIME = 0;
    chargeUpdate();
    digitalWrite(LED_STATE_ACT_PIN, LOW);
    // Capteur de Température
    float TEMP_SENSOR_VALUE = (analogRead(TEMP_SENSOR_PIN) * (5.0 / 1023.0 * 100.0));

    // Capteur d'Humidité
    float HUMIDITY_SENSOR_VALUE = ((analogRead(HUMIDITY_SENSOR_PIN)) * (100 - 10) / (1023) + 10);

    // Capteur de Luminosité
    float BRIGHTNESS_SENSOR_VALUE = 100 - (((analogRead(HUMIDITY_SENSOR_PIN)) * (100 - 15) / (1023) + 15));

    // Capteur de Courant DC
    float CURRENTDC_SENSOR_VALUE = getCurrentDC();

    // Capteur de Courant AC
    float CURRENTAC_SENSOR_VALUE = getCurrentAC();

    // Capteur de Tension DC
    float VOLTAGEDC_SENSOR_RAW = (analogRead(VOLTAGEDC_SENSOR_PIN) * VOLTAGEDC_REF_VOLTAGE) / 1024.0;
    float VOLTAGEDC_SENSOR_VALUE = VOLTAGEDC_SENSOR_RAW / (VOLTAGEDC_RESISTOR2 / (VOLTAGEDC_RESISTOR1 + VOLTAGEDC_RESISTOR2));

    chargeUpdate();
    
    // Communication avec le Raspberry Pi
    if (Serial.available() > 0) {
        digitalWrite(LED_STATE_ACT_PIN, HIGH);
        getChargeCommand();
        sendValue(PACKET_TYPE_SENSOR, TEMP_SENSOR_PIN, TEMP_SENSOR_VALUE); // Température
        delay(2);

        getChargeCommand();
        sendValue(PACKET_TYPE_SENSOR, HUMIDITY_SENSOR_PIN, HUMIDITY_SENSOR_VALUE); // Humidité
        delay(2);

        getChargeCommand();
        sendValue(PACKET_TYPE_SENSOR, BRIGHTNESS_SENSOR_PIN, BRIGHTNESS_SENSOR_VALUE); // BRIGHTNESS
        delay(2);

        getChargeCommand();
        sendValue(PACKET_TYPE_SENSOR, CURRENTDC_SENSOR_PIN, CURRENTDC_SENSOR_VALUE); // Courant DC
        delay(2);

        getChargeCommand();
        sendValue(PACKET_TYPE_SENSOR, CURRENTAC_SENSOR_PIN, CURRENTAC_SENSOR_VALUE); // Courant AC
        delay(2);

        getChargeCommand();
        sendValue(PACKET_TYPE_SENSOR, VOLTAGEDC_SENSOR_PIN, VOLTAGEDC_SENSOR_VALUE); // Tension DC
        delay(2);
    }
    digitalWrite(LED_STATE_ACT_PIN, LOW);
    chargeUpdate();
}

void chargeUpdate() { 
  if(millis() > COMMAND_TIMEOUT_LAST_TIME) {
    if(digitalRead(COMMAND1_CHARGE_PIN)) {
      sendValue(PACKET_TYPE_CHARGE, CHARGE1_RELAY_PIN, !chargeState[CHARGE1_RELAY_PIN]);
      chargeState[CHARGE1_RELAY_PIN] = !chargeState[CHARGE1_RELAY_PIN];
      digitalWrite(CHARGE1_RELAY_PIN, chargeState[CHARGE1_RELAY_PIN]);
      COMMAND_TIMEOUT_LAST_TIME = millis() + COMMAND_TIMEOUT_TIME;
      Serial.print("Exec 1");
      return;
    }
    else if(digitalRead(COMMAND2_CHARGE_PIN)) {
      sendValue(PACKET_TYPE_CHARGE, CHARGE2_RELAY_PIN, !chargeState[CHARGE2_RELAY_PIN]);
      chargeState[CHARGE2_RELAY_PIN] = !chargeState[CHARGE2_RELAY_PIN];
      digitalWrite(CHARGE2_RELAY_PIN, chargeState[CHARGE2_RELAY_PIN]);
      COMMAND_TIMEOUT_LAST_TIME = millis() + COMMAND_TIMEOUT_TIME;
      Serial.print("Exec 2");
      return ;
    }
    else if(digitalRead(COMMAND3_CHARGE_PIN)) {
      sendValue(PACKET_TYPE_CHARGE, CHARGE3_RELAY_PIN, !chargeState[CHARGE3_RELAY_PIN]);
      chargeState[CHARGE3_RELAY_PIN] = !chargeState[CHARGE3_RELAY_PIN];
      digitalWrite(CHARGE3_RELAY_PIN, chargeState[CHARGE3_RELAY_PIN]);
      COMMAND_TIMEOUT_LAST_TIME = millis() + COMMAND_TIMEOUT_TIME;
      Serial.print("Exec 3");
      return;
    }
    else if(digitalRead(COMMAND4_CHARGE_PIN)) {
      sendValue(PACKET_TYPE_CHARGE, CHARGE4_RELAY_PIN, !chargeState[CHARGE4_RELAY_PIN]);
      chargeState[CHARGE4_RELAY_PIN] = !chargeState[CHARGE4_RELAY_PIN];
      digitalWrite(CHARGE4_RELAY_PIN, chargeState[CHARGE4_RELAY_PIN]);
      COMMAND_TIMEOUT_LAST_TIME = millis() + COMMAND_TIMEOUT_TIME;
      Serial.print("Exec 4");
      return;
    }
  }
}

void sendValue(int packettype, int packetid, float value) {
    if (packettype == PACKET_TYPE_SENSOR) {
        if (value > 255) Serial.print("Error: Sensor ID " + String(packetid) + " Value cannot be greater than 255.\n");
        else if (value < -255) Serial.print("Error: Sensor ID " + String(packetid) + " Value cannot be lower than -255.\n");
        else Serial.print("setsensor " + String(packetid) + " " + String(value) + "\n");
    } else {
        if (packetid > 7) Serial.print("Error: Charge ID cannot be greater than 7.\n");
        else if (packetid < 0) Serial.print("Error: Charge ID cannot be lower than 0.\n");
        else if ((int) value > 1) Serial.print("Error: Charge ID " + String(packetid) + " Value cannot be greater than 1.\n");
        else if ((int) value < 0) Serial.print("Error: Charge ID " + String(packetid) + " Value cannot be lower than 0.\n");
        else Serial.print("setcharge " + String(packetid) + " " + String((int) value) + "\n");
    }
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
          chargeState[Buff[1].toInt()] = Buff[2].toInt();
        }
    }
}

float getCurrentDC() {
    unsigned int i = 0;
    float CURRENT_SENSOR_RAW = 0.0, CURRENT_SENSOR_SAMPLE = 0.0, CURRENT_SENSOR_VALUE = 0.0, CURRENT_SENSOR_FILTERED = 0.0;

    for (int i = 0; i < 150; i++) {
        CURRENT_SENSOR_RAW = analogRead(CURRENTDC_SENSOR_PIN);
        CURRENT_SENSOR_SAMPLE += CURRENT_SENSOR_RAW;
        delay(3);
    }
    CURRENT_SENSOR_FILTERED = CURRENT_SENSOR_SAMPLE / 150.0;
    CURRENT_SENSOR_VALUE = (2.5 - (CURRENT_SENSOR_FILTERED * (5.0 / 1023.0))) / 0.066;
    if (CURRENT_SENSOR_VALUE > 30) CURRENT_SENSOR_VALUE = 30.0;
    if (CURRENT_SENSOR_VALUE < 0) CURRENT_SENSOR_VALUE = 0.0;
    return CURRENT_SENSOR_VALUE;
}

float getCurrentAC() {
    static unsigned long update_time_was = millis();
    static float nmax=0, nmin=0, rmax=0, rmin=0, y=ACS758_OFFSET, w=0.4;
    int a0;

    a0 = analogRead(CURRENTAC_SENSOR_PIN); 
    delay(1);   

    y = w*a0 + (1-w)*y;

    if (nmax < a0) nmax = a0;   
    if (nmin > a0) nmin = a0;   
    if (rmax < y)  rmax = y;    
    if (rmin > y)  rmin = y;    

    if (millis()-update_time_was > 1000 )  { 
        update_time_was = millis();
        int __max  = nmax;
        int __min  = nmin;
        nmax = ACS758_OFFSET;
        nmin = ACS758_OFFSET;
        int _rmax = rmax;
        int _rmin = rmin;
        rmax = ACS758_OFFSET;
        rmin = ACS758_OFFSET;

        float navgIpeak =( (__max-__min)/2 * V_PER_LSB) / ACS758_SENSITIVITY;
        float navgIrms  = navgIpeak/sqrt(2);
        float navgPower = navgIrms * MAINS_VOLTS_RMS;

        if (_rmax-_rmin > MIN_LSB) return navgIpeak; 
    }
    return 0.0;
}

int ACS758_GET_OFFSET(void) {
    long ACS758_AVG = 0;
    for (int i=0; i<200; i++) {
        ACS758_AVG += analogRead(CURRENTAC_SENSOR_PIN);
        delay(1);
    }
   return ACS758_AVG/200;
}

void assign_max_min(float val, float *pmax, float *pmin) {
   if (*pmax < val) *pmax = val;
   if (*pmin > val) *pmin = val;
}
