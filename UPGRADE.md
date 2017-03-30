# UPGRADE

## v0.7.x to v0.8.x
* The generate method of the UserUrlGenerator is changed so be careful if you have used to write your custom
url generator strategy.
* UserRepository has a new method named `all`, so be careful with the versions of bridges that implement this interface.

## v0.6.x to v0.7.x
* Reset password and invitation tokens expire, check 1 hour for remember password and 1 week for invitation token 
matches your requirements.
* In case you need to resend an invitation token you must use `ResendInvitationUserCommand` instead `InviteUserCommand`.
