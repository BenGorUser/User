#UPGRADE

##v0.6.x to v0.7.x

* Reset password and invitation tokens expire, check 1 hour for remember password and 1 week for invitation token 
matches your requirements.
* In case you need to resend an invitation token you must use `ResendInvitationUserCommand` instead `InviteUserCommand`.
