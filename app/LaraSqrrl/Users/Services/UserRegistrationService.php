<?php namespace App\LaraSqrrl\Users\Services;

use App\LaraSqrrl\Texts\Entities\IncomingTextObject;
use App\LaraSqrrl\Users\User;

class UserRegistrationService {

    /**
     * @var User
     */
    private $userModel;

    /**
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * @param IncomingTextObject $incomingText
     * @return User|string
     */
    public function checkRegistration(IncomingTextObject $incomingText)
    {
        // check if number is tied to existing user
        if (!$user = $this->userModel->findByPhone($incomingText->getFrom()))
        {
            // user doesn't exist
            if (strtolower($incomingText->getBody()) == "join")
            {
                // user wants to join
                $this->userModel->create(['phone' => $incomingText->getFrom()]);

                // respond with next step
                return "Welcome to LaraSqrrl! To finish registration, we need to know what to call you. So what's your name?";
            }

            // user doesn't want to join and isn't a user
            return "Whoops, looks like you're not a LaraSqrrl user yet. If you want to join, simply reply with \"Join\".";
        }

        // check if user is in process of registering
        switch ($user->registrationStepsCompleted())
        {
            case 1:
                // user has (hopefully) replied with their name, do some validation
                $name = $incomingText->getBody();
                $validate = $user->validateName(['name' => $name]);
                if ($validate)
                {
                    // valid name provided
                    $user->name = $name;
                    $user->save();

                    return "Nice to meet you " . $user->name . "! Last question: Are you a squirrel expert or an enthusiast? Reply with \"Expert\" or \"Enthusiast\".";
                }
                else
                {
                    // invalid name provded
                    return "Whoah, we didn't recognize that name. Names can only include letters, numbers, apostrophes, dashes, and spaces. Please try sending your name again.";
                }

            case 2:
                // user already has name saved, now need to check the role the user sent
                $role = strtolower($incomingText->getBody());
                $validate = $user->validateRole(['role' => $role]);
                if ($validate)
                {
                    // valid role
                    $user->role = $role;
                    $user->save();

                    // determine response based on role
                    switch ($role)
                    {
                        case 'expert':
                            return "You're all set as a squirrel expert! Whenever an enthusiast sends in a photo, you'll receive that photo to analyze.";
                            break;

                        case 'enthusiast':
                            return "You're all set as a squirrel enthusiast! Whenever you're not sure if you're looking at a squirrel, just text us a photo. Our squirrel experts will help you out!";
                            break;
                        default:
                            return "We didn't understand that. Reply with either \"Expert\" or \"Enthusiast\".";
                            break;
                    }
                }
                break;
        }

        // user has completed registration, return the registered user
        return $user;
    }

}
