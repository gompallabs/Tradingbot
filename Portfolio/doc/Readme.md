# A little explanation on model

- *Account*. Everything is not strongly coupled: an account is linked to its owner only by a permission. You MUST setup permissions
- *Permission*. A portfolio is only a view of what is under responsability of a user, acting as an owner or a manager for third-party accounts. 
A portfolio is not linked to a person or a group directly, because it could be owned or managed by a person or by a group of persons. So permissions
are the only thing that matter in this app. In real life, permissions are the consequence of legal / contractual relationships.
Here we manage a portfolio, not contracts or relationships.
- *Transaction*. A transaction is the result of an order. An order can result to many transactions.  
  a transaction can be a fiat and / or an asset movement.
