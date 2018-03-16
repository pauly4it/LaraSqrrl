# LaraSqrrl

Identify and track squirrels via text, now using AWS Rekognition! Created for the November 10th, 2015; February 9th, 2016; May 10th, 2016; and March 13th, 2018 Laravel SF Meetups.

## Local Install

1. Run `php vendor/bin/homestead make` to set up Homestead.
2. Change any parameters in `Homestead.yaml` needed for your local install.
3. Edit your hosts file to add `192.168.10.10   sqrrl.app` (replace the IP with the one in your `Homestead.yaml` file.
4. Copy `.env.example`, rename to `.env`, and fill in necessary variables.
4. Run `vagrant up`.
5. The app is now accessible at `sqrrl.app`.
