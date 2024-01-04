// use std::env;

mod keys;
mod sign;

const WS_URL: &str = "wss://stream.bybit.com/realtime";


fn main() -> Result<(), Box<dyn std::error::Error>> {

    // let mut headers = HeaderMap::new();
    // let mut mac = HmacSha256::new_from_slice(API_SECRET.as_bytes())
    //     .expect("HMAC can take key of any size");
    // mac.update(b"input message");
    //
    //
    // let api_key_header_value = HeaderValue::from_str(API_KEY)?;
    // headers.insert("api-key", api_key_header_value);
    //
    // // Calculate the API signature
    // let expires = (Utc::now() + Duration::new(60, 0)).timestamp();
    // let msg = format!("GET/realtime{}", expires);
    // let sign = calculate_hmac_sha256(API_SECRET, &msg)?;
    // headers.insert("api-signature", HeaderValue::from_str(&sign)?);
    // headers.insert("api-expires", HeaderValue::from_str(&expires.to_string())?);
    //
    // // Establish a WebSocket connection using Tokio and tungstenite.
    // let (ws_stream, _) = connect(Url::parse(WS_URL)?).await?;
    // let ws_stream = ws_stream.await?;
    //
    //
    // // Create a subscription message for the Bybit WebSocket API.
    // let subscribe_message = r#"{"op": "subscribe", "args": ["trade.BTCUSD"]}"#;
    // ws_stream.send(Message::Text(subscribe_message.to_owned())).await?;
    //
    // // Start listening for incoming messages from the WebSocket.
    // while let Some(message) = ws_stream.next().await {
    //     match message {
    //         Ok(Message::Text(response)) => {
    //             // Handle and process the received message.
    //             println!("Received: {:?}", response);
    //         }
    //         Ok(_) => {}
    //         Err(e) => {
    //             eprintln!("WebSocket error: {:?}", e);
    //             break;
    //         }
    //     }
    // }

    Ok(())
}


