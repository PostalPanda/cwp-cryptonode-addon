import os
import time
import threading
import sys
import keyboard
from pyfiglet import Figlet

def countdown_timer():
    for i in range(10, 0, -1):
        print(f"Countdown: {i} seconds", end='\r')
        time.sleep(1)
    print("Countdown: 0 seconds")

def get_user_input():
    try:
        event = keyboard.read_event(suppress=True)
        if event.event_type == keyboard.KEY_DOWN:
            return event.name
    except KeyboardInterrupt:
        pass
    return None

def display_intro():
    # Clear the console screen
    os.system('clear' if os.name == 'posix' else 'cls')

    # Generate and display the PandaNodes title as a text-image
    f = Figlet(font='slant')
    title_text = f.renderText('PandaNodes')
    
    print(title_text)
    print("Developer: Postal Panda Developers")
    print("GitHub: https://github.com/PostalPanda")
    print("Website: https://postalpanda.com/")
    print("This is an open-source software.")
    print("\nThank you for choosing the Panda CryptoNode CWP Addon. This script will install the addon to your server's CentOS Web Panel installation.")
    print("Please press any key to continue, or you can press ctrl+x or x to cancel. The install will start automatically in 10 seconds if no action is received.")

def main():
    current_time = time.strftime("%Y-%m-%d_%H-%M-%S")
    log_file = f"install-{current_time}.log"

    # Redirect stdout and stderr to the log file
    sys.stdout = open(log_file, "a")
    sys.stderr = open(log_file, "a")

    display_intro()

    # Set up a timer thread for the countdown
    countdown_thread = threading.Thread(target=countdown_timer)
    countdown_thread.start()

    user_input = get_user_input()
    if user_input:
        countdown_thread.join()
    else:
        countdown_thread.join(timeout=1)

    if user_input == 'x' or user_input == 'ctrl' or user_input == 'ctrl+x':
        print("Installation canceled.")
        return

    # Install CWP Module Files
    print("Installing CWP Module Files.....")
    time.sleep(2)
    os.system("mv modules/* /usr/local/cwpsrv/htdocs/resources/admin/modules/")

    # Install PandaNodes Addon
    print("Installing PandaNodes Addon....")
    time.sleep(2)
    os.system("mv addons/* /usr/local/cwpsrv/htdocs/resources/admin/addons/")

    # Adding PandaNodes to CWP Admin Navigation
    print("Adding PandaNodes to CWP Admin Navigation....")
    time.sleep(2)
    with open("install/menu-nav.php", "r") as menu_file:
        menu_content = menu_file.read()

    with open("/usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php", "a") as nav_file:
        nav_file.write(menu_content)

    print("Installation completed successfully.")

if __name__ == "__main__":
    main()
