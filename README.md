## TODO App

- Every User can add unlimited TODO
- Every User can see, edit, delete and change state of self TODO
- Run `php artisan schedule:work` to set cron job `app:change-state-todo` for update state of TODO(s) that created more than 2 days.
### Routes
- POST      api/login  
- GET|HEAD  api/logout  
- POST      api/register  
- GET|HEAD  api/todo-list  
- POST      api/todo-list  
- PUT       api/todo-list/change-state/{id}/{state}  
- GET|HEAD  api/todo-list/{id}  
- PUT       api/todo-list/{id}  
- DELETE    api/todo-list/{id}
