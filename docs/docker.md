# Docker

The application is available as a Docker image too, and can be used in 2 ways:

## Build image locally

You can build the image locally in your development environment, by using the **Docker** files in the project root.

To build the docker image you can execute the following command:

```bash
docker build --network=host -t devaslanphp/helper:latest .
```

After the image is created in your docker, you can use the following command to construct your docker container:

```bash
docker-compose up -d
```

This will create a docker container for you in a daemon mode.

> This command uses the file `docker-compose.yml` existing in the project root folder, so before executing the `docker-compose` you need to update the environment variable in this folder.

## Use the Docker hub image

You can use the docker hub image instead of building the image locally [https://hub.docker.com/r/eloufirhatim/helper](https://hub.docker.com/r/eloufirhatim/helper).

> You can refer to the `docker-compose.yml` file to have an example how to use the hub image instead of the local image

> Important: The docker hub image is pushed automatically after a new release tag is created in the repository
