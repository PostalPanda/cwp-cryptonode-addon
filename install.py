import os
import time
import keyboard

def countdown_timer():
    for i in range(10, 0, -1):
        print(f"Countdown: {i} seconds", end='\r')
        time.sleep(1)
    print("Countdown: 0 seconds")

def main():
    print("Thank you for choosing the Panda CryptoNode CWP Addon. This script will install the addon to your server's CentOS Web Panel installation.")
    print("Please press any key to continue, or you can press ctrl+x or x to cancel. The install will start automatically in 10 seconds if no action is received.")
    
    # Set up a timer thread for the countdown
    countdown_thread = None
    try:
        countdown_thread = keyboard.read_event(suppress=True)
        if countdown_thread.event_type == keyboard.KEY_DOWN:
            countdown_timer()
    except KeyboardInterrupt:
        print("\nInstallation canceled.")
        return

    # Install CWP Module Files
    print("Installing CWP Module Files.....")
    time.sleep(2)  # Add a 2-second delay
    os.system("mv modules/* /usr/local/cwpsrv/htdocs/resources/admin/modules/")

    # Install PandaNodes Addon
    print("Installing PandaNodes Addon....")
    time.sleep(2)  # Add a 2-second delay
    os.system("mv addons/* /usr/local/cwpsrv/htdocs/resources/admin/addons/")

    # Adding PandaNodes to CWP Admin Navigation
    print("Adding PandaNodes to CWP Admin Navigation....")
    time.sleep(2)  # Add a 2-second delay
    with open("install/menu-nav.php", "r") as menu_file:
        menu_content = menu_file.read()

    with open("/usr/local/cwpsrv/htdocs/resources/admin/include/3rdparty.php", "a") as nav_file:
        nav_file.write(menu_content)

    print("Installation completed successfully.")

if __name__ == "__main__":
    main()
