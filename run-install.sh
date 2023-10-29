#!/bin/bash

# Install the 'keyboard' package using pip3
pip3 install keyboard

# Check if the installation was successful
if [ $? -eq 0 ]; then
    echo "keyboard package installed successfully."
    echo "Running install.py..."
    
    # Run your install.py script here
    python3 install.py
    
    # Check the exit status of install.py
    if [ $? -eq 0 ]; then
        echo "install.py completed successfully."
    else
        echo "Error: install.py exited with a non-zero status."
    fi
else
    echo "Error: Failed to install the 'keyboard' package."
fi
