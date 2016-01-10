#Domain Model

This component provides two main models containing domain logic that alows handling user common use cases. Both are 
extendable to allow custom use cases. Also multiple value object have been created with their own encapsulated logic.
 
##User
 
Common user extend it to add custom properties and use cases.

##UserGuest

Stores data related to invitation use case. When a invitation is sent to a new user this class is created.

##Value Objects

UserId, UserGuestId, UserEmail, UserPassword, UserRole, UserToken.
