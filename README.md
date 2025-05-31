# DataLogger – Smart Solar Energy Monitoring System
![GitHub repo size](https://img.shields.io/github/repo-size/yassinebouelkheir/DataLogger)
![GitHub contributors](https://img.shields.io/github/contributors/yassinebouelkheir/DataLogger)
![GitHub stars](https://img.shields.io/github/stars/yassinebouelkheir/DataLogger?style=social)

## 📋 Overview

**DataLogger** is a complete IoT-based energy monitoring system designed for solar installations. It enables real-time monitoring, data logging, and control of electrical parameters using a Raspberry Pi and Arduino Mega. This project was developed as part of a final year engineering project in Electrical and Industrial Computing at EST Salé, Université Mohammed V, Morocco.

It combines data acquisition, smart control, and intuitive visualization to optimize energy management in solar-powered systems, particularly in sensitive installations such as hospitals, universities, and industrial labs.

---

## 🎯 Project Objectives

- Measure and monitor electrical parameters: voltage, current, power (AC & DC).
- Display real-time data on a touchscreen and web interface.
- Log data to a MySQL database and export to Excel using the web interface.
- Enable remote control of loads via a relay module.
- Ensure compatibility with MPPT controllers and GEL batteries.
- Implement a secure and ergonomic IoT system for energy auditing.

---

## 🔧 Hardware Components

- **Arduino Mega 2560**
- **8x Arduino Uno/Arduino**
- **2x Raspberry Pi 3**
- **3.5” TFT LCD Touchscreen Display**
- **Current Sensor**: ACS712-30A
- **Voltage Sensor**: 0–25V AC Sensor
- **Temperature and Humidity Sensor**: DHT11
- **Light Sensor**: Photoresistor (LDR)
- **Humidity Sensor**: HIH-4030
- **Steca Solarix MPPT 3020**
- **GEL Battery 12V 200Ah**
- **24V to 220V Inverter**
- **4-Channel Relay Module**
- **Fuses and Circuit Breaker**
- **Solar panels**
- **720p USB Camera**

---

## 🧠 Software Stack

| Layer         | Technology               |
|--------------|--------------------------|
| MCU Firmware | Arduino (C/C++)          |
| SBC Software | Python (Serial Comm)     |
| Web Server   | PHP + MySQL              |
| Front-End    | HTML/CSS, JavaScript     |
| Charts       | Chart.js (for data plots)|
| Export       | PHPExcel (for .xls files)|

---

## 🖥️ Features

- 🌡️ **Live Data Acquisition**: Current, Voltage, Temperature, Light, Humidity.
- 📊 **Real-Time Visualization**: Interactive charts using Chart.js.
- 📱 **Dual Platform Access**: 
  - Web-based dashboard (desktop/tablet/mobile).
  - Local touchscreen interface on Raspberry Pi.
- 🔌 **Remote Control**: 4 load channels via relay module.
- 📁 **Data Export**: Excel reports by hour, day, or month.
- 🔐 **User Authentication**: Admin/User roles for platform access.
- 🧠 **Reliable Storage**: MySQL-based logging for resilience.

---

## 🕸️ System Architecture

```
Sensors → Arduino → Raspberry Pi (via USB Serial) → MySQL → Web Interface + LCD Display
```

- Arduino handles sensor readings and forwards data to Raspberry.
- Raspberry logs data in MySQL and serves front-end interface.
- User interface allows monitoring and relay switching.
- Data is exported using PHPExcel for analysis.

---

## 📸 Screenshots

- 📟 LCD and Raspberry GUI with charts and sensor values.
- 📈 Web dashboard with:
- Instant data display
- 9 types of interactive charts
- Relay control interface
- 📤 Export button to download Excel files

---

## 🚀 Installation

> This assumes basic familiarity with Arduino and Raspberry Pi.

### Prerequisites

- Arduino IDE
- Python 3 (on Raspberry Pi)
- Apache + PHP + MySQL (LAMP stack)
- `chart.js` and `PHPExcel` libraries

### Setup Steps

1. **Flash Each Arduino** with his correspondant `/Arduino/Arduino_*/Arduino_*.ino` file.
2. **Install Python script** on Raspberry to receive and insert data into MySQL. (https://github.com/yassinebouelkheir/DataLogger/blob/main/Raspberry%20Master/Readme.txt)
3. **Deploy PHP Web App** in `/DataLogger/Raspberry Master/Web platform` on Raspberry Pi.
4. **Create MySQL schema** using the `pfe.sql` file.
5. **Connect sensors** as per schematics (refer to hardware section).

---

## 🧪 Testing

- Verify serial connection between Arduino and Raspberry.
- Monitor MySQL inserts using tools like `phpMyAdmin` or CLI.
- Access the web dashboard at `http://<raspberry-ip>/index.php`.
- Interact with relays from both LCD and web interface.

---

## 📂 Directory Structure

```
/DataLogger/
├── Arduino/                # Arduino sketches
├── Raspberry Master/
│   ├── Raspberry Forms/             # CSharp GUI app to be compiled and lunched in the Master Raspberry (using wine)
│   └── Web plateform/               # Web interface (PHP/HTML/CSS/JS/SQL)
│   └── Reciever.py                  # Listenner script (Raspberry-Arduino serial communications using UART)
│   └── Transmitter.py               # Transmitter script (Raspberry-Arduino serial communications using UART)
├── Raspberry Smarthouse/   # Interior controller module
├── Rapport.pdf             # Full guide summary of the project 
└── README.md
```

---

## 📚 References

- [Arduino Documentation](https://www.arduino.cc/)
- [Raspberry Pi Documentation](https://www.raspberrypi.org/documentation/)
- [Chart.js](https://www.chartjs.org/)
- [PHPExcel](https://github.com/PHPOffice/PHPExcel)

---

## 📜 License

This project is licensed under the GNU General Public License v3.0. See [LICENSE](LICENSE) for more details.

---

## 👨‍🎓 Authors

- **Yassine Bouelkheir**  
- **Soumia Chenafi**  
Supervised by: **Mr. Ahmed Akkary**  
EST Salé – Université Mohammed V, Rabat – 2021/2022

---

## 💬 Feedback & Contributions

Feel free to fork, open issues, or suggest improvements via pull requests. Your input is highly appreciated!
