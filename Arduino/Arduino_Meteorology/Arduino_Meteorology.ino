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
   ScriptName    : Arduino_Meteorologie.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 25/04/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine 
*/

#include <SPI.h>
#include <DHT.h>
#include <DHT_U.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>
#include "max6675.h"

int soPin = 4;
int csPin = 5;
int sckPin = 6;
MAX6675 Module(sckPin, csPin, soPin);

#define DHTPIN 2 
#define DHTTYPE DHT11

RF24 radio(9, 10);       
const byte address[6] = "14863";

DHT dht(DHTPIN, DHTTYPE);

void setup() 
{
    pinMode(DHTPIN, INPUT_PULLUP);
    Serial.begin(9600);
    dht.begin();
    radio.begin();                  
    radio.openWritingPipe(address); 
    radio.setPALevel(RF24_PA_MAX); 
    radio.stopListening();          
}

void loop()
{  
    float TEMP1_VALUE = dht.readTemperature();
    char data[24];
    char str_temp[6];
    dtostrf(TEMP1_VALUE, 4, 2, str_temp);
    sprintf(data, "setsensor 5 %s", str_temp);
    radio.write(&data, sizeof(data)); 
    Serial.println(data);            
    delay(1);

    double TEMP2_VALUE = Module.readCelsius();
    dtostrf(TEMP2_VALUE, 4, 2, str_temp);
    sprintf(data, "setsensor 6 %s", str_temp);
    radio.write(&data, sizeof(data));   
    Serial.println(data);          
    delay(1);

    double BRIGHTNESS_VALUE = analogRead(A2);
    dtostrf(BRIGHTNESS_VALUE, 4, 2, str_temp);
    sprintf(data, "setsensor 7 %s", str_temp);
    radio.write(&data, sizeof(data));  
    Serial.println(data);           
    delay(1);

    double HUMIDITY_VALUE = analogRead(A3);
    dtostrf(HUMIDITY_VALUE, 4, 2, str_temp);
    sprintf(data, "setsensor 8 %s", str_temp);
    radio.write(&data, sizeof(data)); 
    Serial.println(data);            
    delay(1);
}
