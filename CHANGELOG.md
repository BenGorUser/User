#CHANGELOG

##v0.7.0

* Reset password expire after 1 hour by default for security reasons.
* Invitation tokens expire after 1 week by default for security reasons.
* Split `InviteHandler` into `InviteHandler` and `ResendInvitationHandler`.
* Changed sanity check in UserRole constructor to only allow strings.
