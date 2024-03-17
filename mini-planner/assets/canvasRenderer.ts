class Point {
    readonly x: number;
    readonly y: number;

    constructor(x: number, y: number) {
        this.x = x;
        this.y = y;
    }
}

class Wall {
    readonly start: Point;
    readonly end: Point;

    constructor(start: Point, end: Point) {
        this.start = start;
        this.end = end;
    }
}

class Room {
    private walls: Wall[];

    constructor(walls: Wall[]) {
        this.walls = walls;
    }

    public getWalls(): Wall[] {
        return this.walls;
    }
}

interface ProjectData {
    [key: string]: Room;
}

class CanvasRenderer {
    private canvas: HTMLCanvasElement;
    private context: CanvasRenderingContext2D;
    private projectData: ProjectData;
    private scaleFactor: number = 1; // Default scale factor
    private offsetX: number = 0;
    private offsetY: number = 0;

    constructor(rawProjectData: any, canvas: HTMLCanvasElement) {
        this.canvas = canvas;
        this.context = canvas.getContext('2d')!;
        this.projectData = this.convertProjectData(rawProjectData);
        this.calculateScaleAndOffset();
    }

    private convertProjectData(rawData: any): ProjectData {
        const projectData: ProjectData = {};

        Object.keys(rawData).forEach(roomId => {
            const rawRoom = rawData[roomId];
            const walls: Wall[] = Object.values(rawRoom.walls).map((rawWall: any) => {
                const points = rawWall.points.map((p: any) => new Point(p.x, p.y));
                return new Wall(points[0], points[1]);
            });

            projectData[roomId] = new Room(walls);
        });

        return projectData;
    }

    private calculateScaleAndOffset(): void {
        let maxX: number = 0;
        let maxY: number = 0;

        Object.values(this.projectData).forEach(room => {
            room.getWalls().forEach(wall => {
                maxX = Math.max(maxX, wall.start.x, wall.end.x);
                maxY = Math.max(maxY, wall.start.y, wall.end.y);
            });
        });

        const scaleX = this.canvas.width / maxX;
        const scaleY = this.canvas.height / maxY;
        this.scaleFactor = Math.min(scaleX, scaleY) * 0.8; // Zoom out by 20%

        // Calculate total project size after scaling
        const projectWidth = maxX * this.scaleFactor;
        const projectHeight = maxY * this.scaleFactor;

        // Calculate offset to center the project
        this.offsetX = (this.canvas.width - projectWidth) / 2;
        this.offsetY = (this.canvas.height - projectHeight) / 2;
    }

    public renderProject(): void {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
        Object.values(this.projectData).forEach(room => this.drawRoom(room));
    }

    private drawRoom(room: Room): void {
        room.getWalls().forEach(wall => this.drawWall(wall));
    }

    private drawWall(wall: Wall): void {
        this.context.beginPath();
        this.context.moveTo((wall.start.x * this.scaleFactor) + this.offsetX, (wall.start.y * this.scaleFactor) + this.offsetY);
        this.context.lineTo((wall.end.x * this.scaleFactor) + this.offsetX, (wall.end.y * this.scaleFactor) + this.offsetY);
        this.context.stroke();
    }
}

window.CanvasRenderer = CanvasRenderer;
