# Advanced Real-Time Sales Analytics System

## Overview
This project is a real-time sales analytics system built using **Laravel** (PHP framework) to manage and analyze sales data. It includes APIs for order management, real-time reporting via WebSockets, AI-driven product recommendations using an external AI API (e.g., OpenAI's ChatGPT), and integration with a weather API (OpenWeather) for dynamic suggestions. The system uses **SQLite** as the database for simplicity, with all database queries written manually.

## AI-Assisted Components
The following parts of the project were assisted by AI:

**WebSockets Server Setup**:
   - AI assisted in generating the initial configuration and setup instructions for the setup WebSocket server.

**Note**: AI-generated code was reviewed and modified to align with project requirements and ensure compatibility with manual implementations.

## Manual Implementation Details
The majority of the project was implemented manually to meet the requirement of avoiding ORMs and prebuilt frameworks for core logic. Below are the key manual components:

1. **Database Logic (SQLite)**:
   - All database queries were written manually using raw SQL in Laravel's query builder.
   - Schema: A single `orders` table was created with columns `id`, `product_id`, `quantity`, `price`, and `date`.
   - Example query for `POST /api/orders`:
     ```sql
     INSERT INTO orders (product_id, quantity, price, date, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)
     ```
   - Real-time metrics (e.g., revenue changes in the last 1 minute) were calculated using time-based SQL queries:
     ```sql
     SELECT
        SUM(CASE WHEN strftime("%Y-%m-%d %H:%M", created_at) = strftime("%Y-%m-%d %H:%M", "now") THEN price ELSE 0 END) -
        SUM(CASE WHEN strftime("%Y-%m-%d %H:%M", created_at) = strftime("%Y-%m-%d %H:%M", "now", "-1 minute") THEN price ELSE 0 END) AS absolute_change
    FROM orders
     ```

2. **Real-Time Reporting (WebSockets)**:
   - Laravel's **Broadcasting** feature with **WebSocketServer** class was used to publish real-time updates.
   - A custom event (`OrderCreated`) was created to broadcast new orders and event (`UpdateAnalyticsEvent`) updated analytics to the frontend.
   - Implementation:
     - When a new order is added via `POST /api/orders`, the event is triggered:
       ```php
       // UpdateAnalyticsEvent
       $data = $this->getUpdateAnalysisEventData();
       event(new UpdateAnalyticsEvent($data));

       // OrderCreated
       event(new OrderCreated($data));
       ```
     - The frontend subscribes to the WebSocket channel to receive updates.
   - Manual logic was written to compute and broadcast analytics updates (e.g., total revenue, top products) whenever a new order is processed.

3. **External API Integration (OpenWeather)**:
   - The OpenWeather API was manually integrated to fetch real-time weather data based on a configured location.
   - Logic was implemented to adjust recommendations:
     - If temperature >= 30°C, promote cold drinks.
     - If temperature =< 30°C, promote hot drinks.
   - Example API call:
     ```php
     Http::get("https://api.openweathermap.org/data/2.5/weather", [
        'q' => 'Cairo',
        'appid' => $appID,
        'units' => 'metric'
     ]);
     $weather = $response->json();
     $temperature = $weather['main']['temp'];
     ```
   - Weather data was combined with logic to suggest dynamic pricing or promotions.

3. **AI Integration (ChatGPT)**:
    The ChatGPT API was manually integrated to fetch real-time product promotion recommendations based on recent sales data
     - API call:
        ```php
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env("OPEN_AI_API_KEY")
        ])->post(env("OPEN_AI_API_ENDPOINT"), [
            'model' => env("OPEN_AI_MODEL"),
            'messages' => [
                [
                    "role" => 'user',
                    'content' => $prompt
                ]
            ]
        ]);
        if (isset($response['error'])) {
            return [
                'error' => $response['error']['message']
            ];
        }
        return json_decode($response->json('choices.0.message.content'));
        ```
     - Building Prompt:
        ```
        $orders = $this->orderRepository->getOrderSentToAI();

        $prompt  = "Based on the following sales data, identify the products (by product_id) with the lowest total sales revenue (price × quantity). ";
        $prompt .= "These are the underperforming products we should consider promoting to improve their sales. ";
        $prompt .= "Return ONLY a ranked array from lowest to highest revenue using this format:\n";
        $prompt .= '[{"product_id": 2, "total_revenue": 1174.04}, ...]';
        $prompt .= "\nDo not include any explanation or extra text — only return the array.\n";
        $prompt .= "\nDo not return empty array.\n";
        $prompt .= "Sales data: ";
        $prompt .= json_encode($orders);
        ```
    - Sales data was combined with logic to suggest dynamic promotions or strategic actions.

4. **API Endpoints**:
   - `POST /api/orders`: Creates an order with product ID, quantity, price, and date, saves to SQLite, triggers WebSocket update, and returns order details.
   - `POST /api/products/create`: Adds a new product with name, description, price and temp_category to SQLite, returning the product date.
   - `GET /api/products`: Fetches all products from SQLite, returning their full data.
   - `GET /api/analytics`: Returns real-time sales data: total revenue, top products, revenue changes, and order count for the last minute.
   - `GET /api/recommendations`: Manually aggregated sales data, sent it to the AI API, and processed the response for frontend display.

5. **Frontend**:
   - A simple frontend was built using **Laravel Blade**, **JavaScript** and **Vite**.
   - Real-time updates were displayed in a dashboard showing total revenue, top products, revenue changes and order count for the last minute .
   - Vanilla JavaScript was written to handle WebSocket events and update the UI dynamically.
   - **Pages:**
     - `/products`: Fetches all products from SQLite and shows a view with their IDs, names, descriptions, prices and temperature categories . Named: 'products'
     - `/orders`: Lists all orders from SQLite in a view with product ID, quantity, price, and date. Named: 'orders'.
     - `/orders/create`: Shows a form to create an order with product ID, quantity, price, date and temperature category. Named: 'orders.create'.
     - `/dashboard`: Displays a dashboard with real-time analytics (total revenue, top products, revenue changes and order counts) via WebSocket. Named: 'dashboard'.
     - `/recommendations`: Sends sales data to ChatGPT API and shows a view with promotion suggestions. Named: 'recommendations'
     - `/suggestions`: Generates strategic suggestions depends on temperature degree which come from **OpenWeather API** and displays them in a view. Named: 'suggestions'.


6. **Error Handling**:
   - Try-catch blocks were used for external API calls to handle network failures gracefully.

## Project Setup and Running Instructions

### Prerequisites
- **PHP** (>= 8.2)
- **Composer** (for Laravel dependencies)
- **Node.js** and **npm** (for frontend assets)
- **SQLite** (included with Laravel)
- **OpenWeather API Key** (sign up at [OpenWeather](https://openweathermap.org/))
- **AI API Key** (e.g., OpenAI for ChatGPT)

### Installation
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/DevYousefM/sales-analytics-system.git
   cd sales-analytics-system
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:
   - Copy the `.env.example` file to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update the `.env` file with the following:
     ```env
     DB_CONNECTION=sqlite
     VITE_API_URL=http://127.0.0.1:8000
     OPEN_AI_API_KEY=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
     OPEN_AI_API_ENDPOINT=https://api.openai.com/v1/chat/completions
     OPEN_AI_MODEL="gpt-4o-mini"
     OPEN_WEATHER_API_KEY=251ac198bdc932c7c8615200d75e3b07

     ```
        -   ```plaintext
            sk-proj-AK1LajYYXgcTOSUJo69mVfHK_MguqE2Lre6njQaP6hVy4GS4UqpgIRNpLpdlowUfq53q-uQqEqT3lbkFJjgzsjaomivBMmte1gGEz6SJouFT02GDx8qb4QfGQaolTI3GeDk37EXQnfP9wXmatnQJm-YXmYA
            ```
        -   ```plaintext
            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
            ```
        -   ```plaintext
            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
            ```
        *if no token work you can sign up at [OpenAI Platform](https://https://platform.openai.com/) to get new token*

4. **Set Up SQLite Database**:
   - Create the SQLite database file:
     ```bash
     touch database/database.sqlite
     ```
   - Run migrations to create the `orders` table:
     ```bash
     php artisan migrate --seed
     ```

5. **Compile Frontend Assets**:
   ```bash
   npm run dev
   ```

6. **Start the Laravel Server**:
   ```bash
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`.
7. **Start the WebSocket Server**:
   ```bash
   php artisan websocket:serve
   ```
8. **Run Update Temperature Command**:
   ```bash
   php artisan config:update-temperature
   ```
   This command will get current temp degree from **OpenWeather API** and update it in DB.

9. **Start the Queue**:
   ```bash
   php artisan schedule:run
   ```
10. **To Update Increment Percent:**
    ```bash
    php artisan config:update-increment-percent
    ```
    **Then enter the percent and press enter**

    *The increment percent applied to temperature-sensitive products based on weather conditions*

### Running the Application
- Access the dashboard at `http://localhost:8000`.
- Use an API client (e.g., Postman) to test the APIs:
  - `POST /orders`: Send a JSON payload like:
    ```json
    {
        "product_id": 1,
        "quantity": 5,
        "price": 10.99,
        "date": "2025-05-11T12:00:00Z"
    }
    ```
  - `GET /analytics`: Returns real-time sales insights.
  - `GET /recommendations`: Returns AI-generated product promotion suggestions.
- The frontend dashboard will update in real-time as new orders are added.

### Manual Testing
1. **API Testing**:
   - Use Postman to send requests to `POST /orders`, `GET /analytics`, and `GET /recommendations`.
   - Verify that the responses match the expected format and data.

   **You can download Postman Collection From [Here](https://drive.google.com/file/d/1OPYxdrRrYcicbS6bZRWeskVXi48vvqV6/view?usp=drive_link)**
2. **Real-Time Updates**:
   - Open the dashboard in a browser and add order page from another browser.
   - Add a new order via the API or form and confirm that the dashboard updates instantly with new revenue and analytics.
3. **AI Recommendations**:
   - Send `GET /recommendations` and verify that the response includes logical product promotion suggestions based on sales.

   **OR**
   - Open the `/recommendations` route in your browser and verify that the displayed product promotions are logically based on sales.

## Project Structure
```
sales-analytics-system/
┣ app/
┃ ┣ Console/
┃ ┃ ┗ Commands/
┃ ┃   ┣ ChangeIncrementPercentConfig.php
┃ ┃   ┣ CreatePhpClassCommand.php
┃ ┃   ┣ DispatchUpdateAnalysisEventCommand.php
┃ ┃   ┣ StartWebSocketServer.php
┃ ┃   ┗ UpdateTemperature.php
┃ ┣ Enum/
┃ ┃ ┗ TempCategoryEnum.php
┃ ┣ Events/
┃ ┃ ┣ OrderCreated.php
┃ ┃ ┣ ProductEvent.php
┃ ┃ ┗ UpdateAnalyticsEvent.php
┃ ┣ Http/
┃ ┃ ┣ Controllers/
┃ ┃ ┃ ┣ Api/
┃ ┃ ┃ ┃ ┗ SalesController.php
┃ ┃ ┃ ┣ Controller.php
┃ ┃ ┃ ┗ SalesController.php
┃ ┃ ┣ Requests/
┃ ┃ ┃ ┣ AddOrderRequest.php
┃ ┃ ┃ ┗ AddProductRequest.php
┃ ┃ ┗ Resources/
┃ ┃   ┗ BaseResponse.php
┃ ┣ Integrations/
┃ ┃ ┣ OpenAI.php
┃ ┃ ┗ OpenWeather.php
┃ ┣ Models/
┃ ┃ ┗ User.php
┃ ┣ Providers/
┃ ┃ ┗ AppServiceProvider.php
┃ ┣ Repositories/
┃ ┃ ┣ ConfigRepository.php
┃ ┃ ┣ OrderRepository.php
┃ ┃ ┗ ProductRepository.php
┃ ┗ Services/
┃   ┣ WebSocket/
┃ ┃ ┃ ┣ Frame.php
┃ ┃ ┃ ┣ Handler.php
┃ ┃ ┃ ┣ WebSocketMessenger.php
┃ ┃ ┃ ┗ WebSocketServer.php
┃   ┣ ConfigService.php
┃   ┣ IntegrationWithAIService.php
┃   ┣ OrderService.php
┃   ┗ ProductService.php
┣ bootstrap/
┃ ┗ app.php
┣ database/
┃ ┣ seeders/
┃ ┃ ┣ DatabaseSeeder.php
┃ ┃ ┗ ProductSeeder.php
┃ ┗ database.sqlite
┣ resources/
┃ ┣ css/
┃ ┃ ┗ app.css
┃ ┣ js/
┃ ┃ ┣ orders/
┃ ┃ ┃ ┣ add-order.js
┃ ┃ ┃ ┗ orders.js
┃ ┃ ┣ services/
┃ ┃ ┃ ┣ api-service.js
┃ ┃ ┃ ┗ websocket-client.js
┃ ┃ ┣ app.js
┃ ┃ ┣ bootstrap.js
┃ ┃ ┣ dashboard.js
┃ ┃ ┣ products.js
┃ ┃ ┣ recommendations.js
┃ ┃ ┗ utilities.js
┃ ┗ views/
┃ ┃ ┣ layout/
┃ ┃ ┃ ┗ master.blade.php
┃ ┃ ┣ orders/
┃ ┃ ┃ ┣ create.blade.php
┃ ┃ ┃ ┗ index.blade.php
┃ ┃ ┣ products/
┃ ┃ ┃ ┗ index.blade.php
┃ ┃ ┣ dashboard.blade.php
┃ ┃ ┣ recommendations.blade.php
┃   ┗ suggestions.blade.php
┣ routes/
┃ ┣ api.php
┃ ┣ console.php
┃ ┗ web.php
┣ .env
┣ .env.example
┣ README.md
```

## Notes
- **AI Usage**: AI was used sparingly to ensure security of the manual WebSocket server.
- **All core logic**: Database queries, WebSocket handling, and external API integrations were written manually for full control and understanding.
- **Scalability**: The system is modular and can be extended with additional features (e.g., user authentication, more complex analytics).
- **Maintainability**: Code follows clean architecture principles with a clear separation of concerns, making it easy to maintain, refactor, and scale over time.
- **Developer Experience**: The folder structure and naming conventions aim to provide a smooth onboarding experience for new developers.
- **Simplicity**: SQLite was used for simplicity during development.

For further assistance, contact the project maintainer or refer to the Laravel documentation.
