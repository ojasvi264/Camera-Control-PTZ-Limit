# Camera Control System

## Introduction
This is a simple web based camera control system, where you can turn on/off your webcam. 
You can also add simple zooming functionality with little bit of adding filters, taking snapshots, and mirroring the camera video.

The workflow of the PTZ limit system is documented inside public/docs folder.

## Axis Communication's PTZ Camera
We also added the feature to limit or restrict the PAN and TILT limit of Axis PTZ Camera for each zoom level. The user set's up the initial limit for 1x zoom,
and based on that limit, using camera features and values, the limit for rest of the zoom is calculated.


### Requirements Camera Control
- WebCam
- WebCam access

### Requirements PTZ Limit System
- PTZ Camera
- PTZ Camera Documentation
- PTZ Web Interface access
- API Accessibility

