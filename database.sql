CREATE TABLE sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    temperature FLOAT,
    humidity FLOAT,
    light_level INT,
    sound_level INT,
    gas_level INT,
    fan_status BOOLEAN,
    led_status BOOLEAN,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE device_controls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device VARCHAR(50),
    status BOOLEAN,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    automation_mode BOOLEAN DEFAULT 0,
    temp_threshold FLOAT DEFAULT 28.0,
    light_threshold INT DEFAULT 800,
    sound_threshold INT DEFAULT 300,
    gas_threshold INT DEFAULT 100,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert initial settings
INSERT INTO system_settings (automation_mode) VALUES (0);

-- Add to system_settings table
ALTER TABLE system_settings 
ADD COLUMN alarm_active BOOLEAN DEFAULT 0,
ADD COLUMN alarm_reason VARCHAR(50) DEFAULT NULL,
ADD COLUMN alarm_timestamp TIMESTAMP NULL;

-- Create alarm_events table
CREATE TABLE alarm_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reason VARCHAR(50) NOT NULL,
    triggered_by VARCHAR(20) NOT NULL, -- 'user', 'system', etc.
    triggered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'active', -- 'active', 'stopped'
    stopped_at TIMESTAMP NULL
);
