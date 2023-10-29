#!/bin/bash

# Create a log file with the current date and time
LOG_FILE="install-$(date +'%Y-%m-%d_%H-%M-%S').log"

# Function to log messages to the log file
log_message() {
    echo "$(date +'%Y-%m-%d %H:%M:%S') - $1" >> "$LOG_FILE"
}

# Check if the 'keyboard' package is already installed
if pip3 show keyboard > /dev/null 2>&1; then
    log_message "'keyboard' package is already installed."
else
    # Install the 'keyboard' package using pip3 and log the step
    log_message "Installing the 'keyboard' package using pip3..."
    pip3 install keyboard >> "$LOG_FILE" 2>&1

    # Check if the installation was successful
    if [ $? -eq 0 ]; then
        log_message "keyboard package installed successfully."
    else
        log_message "Error: Failed to install the 'keyboard' package."
        exit 1
    fi
fi

# Check if the 'pyfiglet' package is already installed
if pip3 show pyfiglet > /dev/null 2>&1; then
    log_message "'pyfiglet' package is already installed."
else
    # Install the 'pyfiglet' package using pip3 and log the step
    log_message "Installing the 'pyfiglet' package using pip3..."
    pip3 install pyfiglet >> "$LOG_FILE" 2>&1

    # Check if the installation was successful
    if [ $? -eq 0 ]; then
        log_message "pyfiglet package installed successfully."
    else
        log_message "Error: Failed to install the 'pyfiglet' package."
        exit 1
    fi
fi

log_message "Running install.py..."

# Run your install.py script and log the step
python3 install.py >> "$LOG_FILE" 2>&1

# Check the exit status of install.py
if [ $? -eq 0 ]; then
    log_message "install.py completed successfully."
else
    log_message "Error: install.py exited with a non-zero status."
fi
