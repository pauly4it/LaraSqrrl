# LaraSqrrl

Identify and track squirrels via text. Created for the November 10th Laravel SF Meetup.

## Local Install

1. Run `php vendor/bin/homestead make` to set up Homestead.
2. Change any parameters in `Homestead.yaml` needed for your local install.
3. Edit your hosts file to add `192.168.10.10   sqrrl.app` (replace the IP with the one in your `Homestead.yaml` file.
4. Copy `.env.example`, rename to `.env`, and fill in necessary variables.
4. Run `vagrant up`.
5. The app is now accessible at `sqrrl.app`.
