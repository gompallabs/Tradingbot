use std::error::Error;
use websocket::ClientBuilder;
use websocket::message::OwnedMessage;
use tokio::stream::StreamExt;

#[tokio::main]
async fn main() -> Result<(), Box<dyn Error>> {
    let url = "wss://capi.bitget.com/realtime/websocket"; // Bitget WebSocket API URL

    // Create a WebSocket client and connect to the Bitget API URL
    let (mut socket, response) = ClientBuilder::new(url)
        .connect(None)
        .await?;

    println!("Connected to Bitget WebSocket API: {:?}", response);

    // Define the WebSocket subscription message
    let subscription_message = r#"{
        "op": "subscribe",
        "args": ["orderbook", "BTC/USDT"]
    }"#;

    // Send the subscription message to the WebSocket server
    socket.send(OwnedMessage::Text(subscription_message.to_string()))
        .await?;

    // Start processing WebSocket messages
    while let Some(Ok(message)) = socket.next().await {
        match message {
            OwnedMessage::Text(text) => {
                // Handle text messages received from the WebSocket
                println!("Received text message: {:?}", text);
            }
            OwnedMessage::Binary(data) => {
                // Handle binary messages received from the WebSocket
                println!("Received binary message: {:?}", data);
            }
            _ => {
                // Handle other WebSocket message types if needed
            }
        }
    }

    Ok(())
}
