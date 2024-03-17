export {};

declare global {
    interface Window {
        CanvasRenderer: typeof CanvasRenderer;
    }
}
