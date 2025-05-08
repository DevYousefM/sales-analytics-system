const ws = new WebSocket("ws://127.0.0.1:8080");

ws.onopen = () => console.log("WebSocket connected ✅");
ws.onerror = (err) => console.error("WebSocket error:", err);
ws.onmessage = (e) => console.log("Message:", e.data);
