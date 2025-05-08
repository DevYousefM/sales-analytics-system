import {
    onMessage,
    openSocket,
    initializeWebSocket,
} from "./services/websocket-client";

const ws = initializeWebSocket();
openSocket(ws, "analytics", "update-analytics");

onMessage(ws, "analytics", "update-analytics", (data) => {
    console.log("data", data);
});
