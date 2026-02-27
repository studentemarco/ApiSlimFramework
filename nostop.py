#!/usr/bin/env python3
import sys
import time

MAX_MINUTES = 120  # 2 ore

def ask_continue():
    try:
        ans = input("\nSono passate 2 ore. Continuare? [s/N]: ").strip().lower()
        return ans == "s"
    except EOFError:
        return False

def main():
    minutes = 0

    try:
        while True:
            print(".", end="", flush=True)
            time.sleep(60)
            minutes += 1

            if minutes >= MAX_MINUTES:
                if sys.stdin.isatty():
                    if ask_continue():
                        minutes = 0
                        print()  # va a capo prima di riprendere
                    else:
                        print("\nTerminato.")
                        break
                else:
                    # niente TTY → termina automaticamente
                    print("\nTerminato (no TTY).")
                    break

    except KeyboardInterrupt:
        print("\nInterrotto dall'utente.")

if __name__ == "__main__":
    main()
