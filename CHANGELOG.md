#CHANGELOG

This changelog references the relevant changes (bug and security fixes) done in 0.x minor versions.

To get the diff for a specific change, go to https://github.com/BenGorUser/User/commit/XXX where XXX is the change hash
To get the diff between two versions, go to https://github.com/BenGorUser/User/compare/v0.6.0...v0.7.0

##v0.8.0
* Introduced hacks to prevent Doctrine's bug with nullable embeddables.
* [Travis CI] Dropped support for HHVM and added for PHP 7.1.

##v0.7.1
* Fixed bug related with invitation token acceptance.

##v0.7.0
* Added user of id query handler.
* UserOfRememberPasswordToken and UserOfInvitationToken query handlers now checks if the token is expired.
* Reset password expire after 1 hour by default for security reasons.
* Invitation tokens expire after 1 week by default for security reasons.
* Split `InviteHandler` into `InviteHandler` and `ResendInvitationHandler`.
* Changed sanity check in UserRole constructor to only allow strings.
