extern crate ring;
use ring::{aead, pbkdf2, rand};
use std::fs::File;
use std::io::{Write};
use ring::aead::BoundKey;
use ring::rand::SecureRandom;

const KEY_LEN: usize = 32;
const NONCE_LEN: usize = 12;

pub fn encrypt_and_save_keys(api_key: &str, api_secret: &str, file_path: &str, encrypt_key: &str) -> Result<(), std::io::Error> {
    let rng = rand::SystemRandom::new();
    let mut random_bytes = [0u8; 4]; // You can adjust the array size as needed
    let key_material = {
        let mut key_material = vec![0u8; KEY_LEN];
        rng.fill(&mut key_material).expect("Failed to generate random bytes");
        key_material
    };

    let nonce = {
        let mut nonce_bytes = [0u8; NONCE_LEN];
        if let Err(err) = rng.fill(&mut nonce_bytes) {
            return Err(std::io::Error::new(std::io::ErrorKind::Other, err));
        }

        rng.fill(&mut nonce_bytes)?;
        aead::Nonce::try_assume_unique_for_key(&nonce_bytes)
            .map_err(|_| std::io::Error::new(std::io::ErrorKind::Other, "Invalid nonce"))?
    };


    let sealing_key = {
        let unbound_key = aead::UnboundKey::new(&aead::AES_256_GCM, &random_bytes)?;
        let sealing_key = aead::SealingKey::new(unbound_key, nonce)?;
        sealing_key
    };


    let additional_data: aead::Aad<_> = aead::Aad::empty();
    additional_data.extend_from_slice(additional_data.as_ref());


    let mut ciphertext = Vec::new();
    aead::SealingKey::seal_in_place_append_tag(&sealing_key, additional_data, &mut ciphertext)?;

    let mut secret_file = File::create(file_path)?;
    secret_file.write_all(&ciphertext)?;

    let encrypt_key_bytes = encrypt_key.as_bytes();
    let mut salt = [0u8; 8]; // You may want to use a unique salt
    rng.fill(&mut salt)?;

    let mut derived_key = [0u8; KEY_LEN];
    let derived_key = pbkdf2::derive(
        pbkdf2::PBKDF2_HMAC_SHA256,
        std::num::NonZeroU32::new(10000).unwrap(),
        encrypt_key_bytes,
        &salt,
        &mut derived_key,
    );

    secret_file.write_all(&salt)?;
    secret_file.write_all(&derived_key)?;

    Ok(())
}


// Decrypt the API keys from a file using the DECRYPT_KEY
// pub fn decrypt_keys_from_file(file_path: &str, decrypt_key: &str) -> Result<(String, String), std::io::Error> {
//     let mut secret_file = File::open(file_path)?;
//
//     let mut salt = [0u8; 8];
//     secret_file.read_exact(&mut salt)?;
//
//     let decrypt_key_bytes = decrypt_key.as_bytes();
//     let derived_key = pbkdf2::derive(
//         pbkdf2::PBKDF2_HMAC_SHA256,
//         std::num::NonZeroU32::new(10000).unwrap(),
//         decrypt_key_bytes,
//         &salt,
//     );
//
//     let sealed_data: Vec<u8> = secret_file.bytes().collect::<Result<Vec<u8>, _>>()?;
//
//     let key = aead::OpeningKey::new(&aead::AES_256_GCM, &sealed_data, &derived_key)?;
//
//     let nonce = aead::Nonce::assume_unique_for_key([0u8; NONCE_LEN]);
//     let additional_data = b""; // Additional data (empty in this example)
//
//     let mut plaintext = vec![0u8; sealed_data.len()];
//     aead::open_in_place(&key, nonce, additional_data, 0, &mut plaintext)?;
//
//     let api_key = String::from_utf8_lossy(&plaintext[..plaintext.len() / 2]).to_string();
//     let api_secret = String::from_utf8_lossy(&plaintext[plaintext.len() / 2..]).to_string();
//
//     Ok((api_key, api_secret))
// }
