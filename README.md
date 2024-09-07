# Laravel Basic

This is a brief description of my project. It provides an overview of what the project does and its main features.

## Features

### User
- **Login**: Users can log in using their email and password credentials. Upon successful login, the system generates an authentication token with a lifetime of 10 minutes and a refresh token.
- **Register**: Register users via input data. Before creating the account, the system checks if the provided email does not already exist in the database. If the email is unique, the user account is created.
- **Update information**: Update user information through the user id embedded in the token
- **Get information**: Retrieve user information via token
- **Refresh Token**: Used to retrieve a new token

### Role
- **Get all role**: Retrieve all roles in the database
- **Get all in trash**: Retrieve all roles in the trash
- **Create new role**: Create a new role, provided that the role name is unique in the database
- **Update role**: Update role based on id
- **Move role to trash**: Move role to trash based on id
- **Delete role**: Permanently delete roles based on id
- **Restore role**: Restore role based on id

### Brand
- **Get all brand**: Retrieve all brands in the database
- **Get all in trash**: Retrieve all brands in the trash
- **Create new brand**: Create a new brand, provided that the brand name is unique in the database
- **Update brand**: Update brand based on id
- **Move brand to trash**: Move brand to trash based on id
- **Delete brand**: Permanently delete brand based on id
- **Restore brand**: Restore brand based on id

### Parent Category
- **Get all parent category**: Retrieve all parent category in the database
- **Get all in trash**: Retrieve all brands in the trash
- **Create new parent category**: Create a new parent category, provided that the input data brandId must exist in the database and the name of the parent category must be unique based on the foreign key brand_id
- **Update parent category**: Update parent category based on id
- **Move parent category to trash**: Move parent category to trash based on id
- **Delete parent category**: Permanently delete parent category based on id
- **Restore parent category**: Restore parent category based on id

### Child Category
- **Get all child category**: Retrieve all child category in the database
- **Get all in trash**: Retrieve all brands in the trash
- **Create new child category**: Create a new child category, provided that the input data parentCategoryId must exist in the database and the name of the child category must be unique based on the foreign key parent_category_id
- **Update child category**: Update child category based on id
- **Move child category to trash**: Move child category to trash based on id
- **Delete child category**: Permanently delete child category based on id
- **Restore child category**: Restore child category based on id

### Product
- **Filter product**: Retrieve products according to input data: name, color, brand id, parent category id, child category id, price and sort by('price_low', 'price_high', 'newest')
- **Get all in trash**: Retrieve all brands in the trash
- **Create new product**: Create a new product, provided that the input data brandId, parentCayegoryId and childCategory must exist and be compatible in the database.
- **Update product**: Update product based on id
- **Move product to trash**: Move product to trash based on id
- **Delete product**: Permanently delete product based on id
- **Restore product**: Restore product based on id