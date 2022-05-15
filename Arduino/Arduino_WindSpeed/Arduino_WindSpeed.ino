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
   ScriptName    : Arduino_WindSpeed.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 25/04/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

#include <SPI.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>

RF24 radio(9, 10);       
const byte address[6] = "14863";

long prevT;

float velocity = 0;
float deltaT;
float R1 = 30000.0;
float R2 = 7500.0;

void setup() 
{
    Serial.begin(9600);
    radio.begin();                  
    radio.openWritingPipe(address); 
    radio.setPALevel(RF24_PA_MAX); 
    radio.stopListening();

    pinMode(2, INPUT);
    pinMode(4, OUTPUT);
    attachInterrupt(digitalPinToInterrupt(2), readEncoder, RISING);
}

void loop()
{  
    char data[24];
    char str_temp[6];

    double value = analogRead(A0);
    double vOUT = (value*5.0)/1024.0;
    double vIN = vOUT/(R2/(R1+R2));
    double V1 = 4.6+(0.2480485*vIN);
    if(V1 < 4.7) V1 = 0;

    value = analogRead(A1);
    vOUT = (value*5.0)/1024.0;
    vIN = vOUT/(R2/(R1+R2));
    double V2 = -4.876997*pow(10,-2)+(0.5189756*vIN);
    if(V2 < 0) V2 = 0;

    int sensorValue = analogRead(A2); 
    if(sensorValue > 500) digitalWrite(4,HIGH);
    else digitalWrite(4,LOW);

    if (deltaT>5 || deltaT<0.08) velocity = 0;

    dtostrf(V1, 1, 2, str_temp);
    sprintf(data, "setsensor 9 %s", str_temp);
    radio.write(&data, sizeof(data));             

    dtostrf(V2, 1, 2, str_temp);
    sprintf(data, "setsensor 10 %s", str_temp);
    radio.write(&data, sizeof(data));             

    dtostrf(velocity, 1, 2, str_temp);
    sprintf(data, "setsensor 11 %s", str_temp);
    radio.write(&data, sizeof(data));             
}

void readEncoder()
{
    long currT = micros();
    deltaT = ((float) (currT - prevT))/1.0e6;

    velocity = 1/deltaT;
    velocity = velocity*60;
    Serial.println(velocity);
    prevT=currT;
}
