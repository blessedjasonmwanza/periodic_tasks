# Periodic tasks API
API to help you add, fetch, and send reminder tasks to users

> Note: this API has not yet been tested, it is an answer to how technical question can be implemented


## Requirements
- [PHP](https://php.net) 7.1 and above. 
- Knowledge on how [Medoo](https://medoo.in) db wrapper works.
## Installation
 > assuming you're using localhost

For running this example, you need to have a server application that can interpret and compile php in your local machine.

Please see configuration section below for configuring your DB Keys.

```bash
1: git clone https://github.com/omise/omise-php
```

2: Copy the files and folders to the root of your domain folder/path 

## Configuration
Next, you need to **configure** your DBm keys.  
So, we have 1 files that you need to change:
- `config/db.php`

```php
//e.g
$db = new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'tasks',
    'username' => 'db_user_name',
    'password' => '123456saqs1'
]);
```

## Available API endpoint Actions (uses POST method)

> To access the api endpoints, you'd have to make ```POST``` reguest to your domain patch/api/

domain name path example: ```http://localhost/mywebsite/api/```

> Note: the action param is mandatory

## API response format (json object)
```javascript
//you should check the error status first.
// true means there was an error and false means your response is good.
{
    error: true //or false
    data: //[]array for fetch/read actions and
}
```

## ACTIONS

### add
Required POST params to add a task
 - user_id (int)
 - description (text)
 - start (date) a valid date when user intends to start the activity/task
 - end (date) a valid date when user intends to be done with the activity

### user_tasks
> View user tasks

Required ```get``` params
- user_id

### due_this_week
> View user tasks that are due this week

Required ```get``` params
- user_id

### due_next_week
> View user tasks that are due next week

Required ```get``` params
- user_id

### between
> View user tasks that are between a date range

Required ```get``` params
- user_id
- start (date) a valid date when user intends to start the activity/task
 - end (date) a valid date when user intends to be done with the activity

 ### between
 > send reminder or tasks past due

 Required ```get``` params
 - user_id
