#include <SPI.h>
#include <nRF24L01.h>
#include <RF24.h>

RF24 radio(9, 10);       
const byte address[6] = "14863";
const byte address2[6] = "26957";
void setup() 
{
    Serial.begin(9600);
    radio.begin();
    
    radio.openWritingPipe(address2);                  
    radio.openReadingPipe(1, address);
    radio.disableAckPayload();
    
    radio.setPALevel(RF24_PA_MAX);
    radio.stopListening(); 

    for(int i = 2; i <= 4; i++)
    {
        pinMode(i, OUTPUT);
        digitalWrite(i, LOW);
    }
    digitalWrite(2, HIGH);
}
void loop()
{   
    radio.stopListening();
    delay(10);
    char data[24];
    char str_temp[6];
             
    getChargeCommand();
    delay(1);
    
    digitalWrite(3, HIGH);
    radio.startListening();
    delay(13);
    if (radio.available()) 
    {
        char text[24];
        radio.read(&text, sizeof(text));
        Serial.println(text);
    }
    digitalWrite(3, LOW);
}
void getChargeCommand() {
    String Buff[10];
    int StringCount = 0;
    String data = Serial.readStringUntil('\n');
    if (data.length() > 1) {
        digitalWrite(4, HIGH);
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
        char datax[24];
        sprintf(datax, "setcharge %i %i", int(Buff[1].toInt()), bool(Buff[2].toInt()));
        radio.write(&datax, sizeof(datax));
        digitalWrite(4, LOW);
    }
}
