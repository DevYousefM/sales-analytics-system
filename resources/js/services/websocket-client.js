export const initializeWebSocket = () => {
    return new WebSocket("ws://127.0.0.1:8080");
};

export const openSocket = (ws, $channel, $event) => {
    ws.onopen = () => {
        const subscribeMessage = {
            type: "subscribe",
            channel: $channel,
            event: $event,
        };
        ws.send(JSON.stringify(subscribeMessage));
        // console.log("Subscribed to", $channel, $event);
    };
};

export const onMessage = (ws, $channel, $event, callback) => {
    ws.onmessage = (e) => {
        const response = JSON.parse(e.data);

        if (response.channel === $channel && response.event === $event) {
            callback(response.data);
        }
    };
};
