
# STEP 1: Build stage using .NET 8 SDK
# ------------------------------------
# This image includes everything need to compile and build .net 9 apps
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build

# Set work dir in the container where all commands will run
WORKDIR /app

#copy only the .csproj files to leverage dockler layer caching
# This helps avoid restoring packages everythime the code changes
COPY *.csproj .

# restore nuget pacjages based on .csproj file
RUN dotnet restore

# copy the rest intpo the container
COPY . .

#Build and publish the app in relase mode into app .out folder
RUN dotnet publish -c Release -o out


# STEP 2: Rntime stage using asp.net core runtimr 8
# This imge is smaller than the SDK and optimized for running ASP.NET Core apps
FROM mcr.microsoft.com/dotnet/aspnet:8.0

# Set the working directory for the runtime contaioner
WORKDIR /app

# Copy the published build output from the sdk stage
COPY --from=build /app/out .


# Set the entrypoint command to run the compiled dll
ENTRYPOINT ["dotnet", "debraApp.dll"]
